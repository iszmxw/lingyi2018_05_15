<div class="footer" >
    <div class="pull-right">
        您本次登录信息 ：{{ date('Y-m-d H:i:s',$admin_data['login_time']) }}&nbsp;&nbsp;{{ $admin_data['login_position'] }}
    </div>
    <div>
        <strong>Copyright</strong> 零壹新科技（深圳）有限公司&copy; 2017-2027
    </div>
</div>

<script>

    $(function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        $('select.styled').customSelect();
    });

</script>