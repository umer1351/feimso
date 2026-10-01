<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="siddhi-cms-pages">
    <div class="container">
        <div class="cms-page pt-5 pb-5">
            <h1 class="head"></h1>
            <div class="content">
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script type="text/javascript">
    var slug = "<?php echo $slug; ?>";
    var ref = database.collection('cms_pages').where('slug', '==', slug);
    jQuery("#overlay").show();
    ref.get().then(async function (snapshots) {
        var cms = snapshots.docs[0].data();
        $('.head').html(cms.name);
        $('.content').html(cms.description);
        jQuery("#overlay").hide();
    });
</script><?php /**PATH /home/bubuggggycarpool/web/resources/views/cms/index.blade.php ENDPATH**/ ?>