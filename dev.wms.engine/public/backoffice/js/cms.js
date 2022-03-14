$(function () {
    var defautl_option = {
        tabsize: 2,
        height: 140,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]

    };
    var fr_option = Object.assign({}, defautl_option);
    var en_option = Object.assign({}, defautl_option);
    var fr_selector = '#wms_service_metiermanagerbundle_cms_translations_fr_cmstContent, #wms_service_metiermanagerbundle_slider_translations_fr_sldtContent, ' +
        '#wms_service_metiermanagerbundle_service_translations_fr_srvtContent, #wms_service_metiermanagerbundle_realisation_type_translations_fr_rttContent, ' +
        '#wms_service_metiermanagerbundle_realisation_translations_fr_rlstContent, #wms_service_metiermanagerbundle_avis_translations_fr_avstContent';
    var en_selector = '#wms_service_metiermanagerbundle_cms_translations_en_cmstContent, #wms_service_metiermanagerbundle_slider_translations_en_sldtContent, ' +
        '#wms_service_metiermanagerbundle_service_translations_en_srvtContent, #wms_service_metiermanagerbundle_realisation_type_translations_en_rttContent, ' +
        '#wms_service_metiermanagerbundle_realisation_translations_en_rlstContent, #wms_service_metiermanagerbundle_avis_translations_en_avstContent';
    var mg_selector = '#wms_service_metiermanagerbundle_cms_translations_mg_cmstContent, #wms_service_metiermanagerbundle_slider_translations_mg_sldtContent, ' +
        '#wms_service_metiermanagerbundle_service_translations_mg_srvtContent, #wms_service_metiermanagerbundle_realisation_type_translations_mg_rttContent, ' +
        '#wms_service_metiermanagerbundle_realisation_translations_mg_rlstContent, #wms_service_metiermanagerbundle_avis_translations_mg_avstContent';
    var title_fr_selector = fr_selector.replace(/Content/g, 'Title');

    fr_option.lang = 'fr-FR';
    $(fr_selector).summernote(fr_option);

    en_option.lang = 'en-En';
    $(en_selector).summernote(en_option);

    $(mg_selector).summernote(defautl_option);

    $('.kl-submit-summernote').on('click', function(e) {
        var is_invalid_title = $(title_fr_selector).val() == '' || $(title_fr_selector).val() == undefined;
        var is_invalid_content = $(fr_selector).summernote('isEmpty');
        if(is_invalid_title || is_invalid_content) {
            if(is_invalid_title) notify('danger', global_utils.texts.title_required);
            if(is_invalid_content) notify('danger', global_utils.texts.content_required);

            // cancel submit
            e.preventDefault();
        }

    })
});