<?php

namespace Dedemao\Payjs\Http\Controllers\Admin;

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Modal;
use Dedemao\Payjs\Facades\OrderService;
use Dedemao\Payjs\Models\PayjsOrders;
use Dcat\Admin\Grid;
use Dedemao\Payjs\Actions\Refund;
use Illuminate\Http\Request;
use Dcat\Admin\Actions\Response;

class OrderController extends AdminController
{

    protected $title = '订单列表';

    protected $description = [
        'index' => '订单列表',
    ];

    public function grid()
    {
        return Grid::make(new PayjsOrders(), function (Grid $grid) {

            $extension = Admin::extension()->get('dedemao.dcat-admin-payjs');
            if (! method_exists($extension, 'settingForm')) {
                return;
            }
            $settingButton = Modal::make()
                ->lg()
                ->title($title = '支付设置')
                ->body($extension->settingForm())
                ->button("<button class='btn btn-primary'><i class=\"fa feather icon-settings\"></i> &nbsp;{$title}</button>");
            $grid->tools($settingButton);
            $grid->tools('<a target="_blank" href="' . url('pay/test') . '" class="btn btn-primary btn-mini btn-outline"><i class="d-none d-sm-inline fa fa-send-o"></i>&nbsp;测试支付</a>');

            $grid->model()->orderBy('id', 'DESC');

            $grid->column('id', 'ID')->sortable();
            $grid->column('type', '支付类型')->display(function ($type) {
                switch ($type) {
                    case 'alipay':
                        return "<span class=\"label bg-info\">支付宝</span>";
                    case 'weixin':
                        return "<span class=\"label bg-success\">微信</span>";
                }
            });
            $grid->column('out_trade_no', '订单号')->copyable();
            $grid->column('subject', '订单标题');
            $grid->column('transaction_tid', '支付流水号');
            $grid->column('total_fee', '订单金额')->sortable();
            $grid->column('pay_at', '支付时间');
            $grid->column('created_at', trans('admin.created_at'))->display(function ($time) {
                return date('Y-m-d H:i:s', strtotime($time));
            });
            $grid->column('status', '订单状态')->display(function ($status) {
                switch ($status) {
                    case 0:
                        return "<span class=\"label bg-success\">已支付</span>";
                    case 1:
                        return "<span class=\"label bg-default\">待支付</span>";
                    case 2:
                        return "<span class=\"label bg-danger\">已退款</span>";
                }
            });

            $grid->quickSearch(['out_trade_no', 'transaction_tid', 'outer_tid']);

            $grid->disableFilterButton();
            $grid->disableCreateButton();
            $grid->showColumnSelector();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->row->status != 1) {
                    $actions->disableDelete();
                }
                if ($actions->row->status == 0) {
                    $actions->append(new Refund);
                }
                $actions->disableEdit();
            });
        });
    }


    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {

        return Show::make($id, new PayjsOrders(), function (Show $show) {
            $show->field('id', 'ID');
            $labelStyle = $show->model()->type == 'alipay' ? 'info' : 'success';
            $show->field('type', '支付类型')->as(function ($type) {
                return getPayChannelName($type);
            })->label($labelStyle);
            $show->field('out_trade_no', '订单号');
            $show->field('subject', '订单标题');
            $show->field('outer_tid', 'PAYJS 平台订单号');
            $show->field('transaction_tid', '支付流水号');
            $show->field('total_fee', '订单金额');
            $show->field('status', '订单状态')->as(function ($status) {
                switch ($status) {
                    case 0:
                        return "已支付";
                    case 1:
                        return "待支付";
                    case 2:
                        return "已退款";
                }
            });
            $show->field('buyer_info', '支付者信息');
            $show->field('pay_at', '支付时间');
            $show->field('created_at', trans('admin.created_at'));
            $show->field('updated_at', trans('admin.updated_at'));

            $show->panel()->tools(function (Show\Tools $tools) {
                $tools->disableDelete();
                $tools->disableEdit();
            });;
        });
    }

    protected function refund(Request $request)
    {
        if($request->isMethod('post')){
            $orderModel = PayjsOrders::query()->find(intval($request->post('id')));
            $fefundFee = floatval($request->post('total_fee'));
            $response = new Response();
            if($fefundFee<0.01){
                return $response->error('退款金额不能低于0.01元')->send();
            }
            if($fefundFee>$orderModel->total_fee){
                return $response->error('退款金额不能超过订单金额')->send();
            }
            $result = OrderService::refund($orderModel->out_trade_no);
            if($result['status']!='success'){
                return $response->error($result['msg'])->send();
            }else{
                return $response->success($result['msg'])->send();
            }
        }else{
            $id = intval($request->get('id'));
            $orderModel = PayjsOrders::query()->find($id);
            return Form::make($orderModel, function (Form $form) use ($orderModel) {
                $form->action('payjs/order/refund');
                $form->width(9, 3);
                $form->hidden('id')->value($orderModel->id);
                $form->text('total_fee', '退款金额（元）')->default($orderModel->total_fee);
            });
        }

    }

    public function form()
    {
        return Form::make(new PayjsOrders());
    }

    public function destroy($id)
    {
        $orderModel = PayjsOrders::query()->find(intval($id));
        $response = new Response();
        if ($orderModel->status!=1) {
            return $response->error('当前状态不能删除')->send();
        }
        return parent::destroy($id);
    }
}
