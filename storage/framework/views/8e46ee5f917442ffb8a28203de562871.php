<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="container position-relative extra-page">
    <div class="col-md-12 mb-3 mt-5 pb-5 pt-3">
        <div class="privacy"></div>
    </div>
</div>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<script>
    ('user_uuid  ' + user_uuid);
    var id = user_uuid;
    var database = firebase.firestore();
    var ref = database.collection('settings').doc('privacyPolicy');
    $(document).ready(function () {
        ref.get().then(async function (snapshots) {
            var user = snapshots.data();
            (user);
            $(".privacy").html(user.privacy_policy);
            jQuery("#data-table_processing").hide();
        })
    });
</script><?php /**PATH /home/bubuggggycarpool/web/resources/views/static/privacypolicy.blade.php ENDPATH**/ ?>