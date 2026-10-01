<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="siddhi-home-page">
    <div class="bg-primary px-3 d-none mobile-filter pb-3">
        <div class="row align-items-center">
            <div class="input-group rounded shadow-sm overflow-hidden col-md-9 col-sm-9">
                <div class="input-group-prepend">
                    <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block">
                        <i class="feather-search"></i>
                    </button>
                </div>
                <input type="text" class="shadow-none border-0 form-control" placeholder="Search for vendors or dishes">
            </div>
            <div class="text-white col-md-3 col-sm-3">
                <div class="title d-flex align-items-center">
                    <a class="text-white font-weight-bold ml-auto" data-toggle="modal" data-target="#exampleModal"
                       href="#"><?php echo e(trans('lang.filter')); ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="cabLandingPage">
    </div>
</div>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="https://unpkg.com/geofirestore@5.2.0/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="<?php echo e(asset('vendor/slick/slick.min.js')); ?>"></script>
<script type="text/javascript">
    var database = firebase.firestore();
    var cabLandingPageRef = database.collection('sections').where('id', '==', section_id);
   
    jQuery("#overlay").show();
    cabLandingPageRef.get().then(async function (snapshots) {
        var cabLandingPageData = snapshots.docs[0].data();
        if (cabLandingPageData.cab_service_template && cabLandingPageData.cab_service_template != "" && cabLandingPageData.cab_service_template != undefined) {
            $('.cabLandingPage').html(cabLandingPageData.cab_service_template);
            jQuery("#overlay").hide();
        }
    });
</script>
<?php echo $__env->make('layouts.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bubuggggycarpool/web/resources/views/home_page/cab_home.blade.php ENDPATH**/ ?>