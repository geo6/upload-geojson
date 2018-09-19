import initView from './fn/view';

window.app = window.app || {};

$(document).ready(() => {
    initView();

    $('[data-toggle="tooltip"]').each((i, element) => {
        const card = $(element).closest('.card');
        const index = $(card).index('.card');

        $(element).tooltip({
            html: true,
            title: window.app.warnings[index].join('<br>')
        });
    });

    $('.form-check-input').on('change', (event) => {
        const checked = $(event.target).prop('checked');
        const card = $(event.target).closest('.card');
        const { success } = $(card).data();

        const count = $('.form-check-input:checked').length;

        if (checked === true) {
            $(card).removeClass('bg-success bg-warning').addClass('bg-primary');
        } else if (success === 1) {
            $(card).removeClass('bg-primary').addClass('bg-success');
        } else if (success === 0) {
            $(card).removeClass('bg-primary').addClass('bg-warning');
        }

        $('#count-files').text(count);
        $('#btn-save').prop('disabled', (count < 1));
    }).trigger('change');

    $('#modal-view').on('show.bs.modal', (event) => {
        const link = event.relatedTarget;
        const index = $('a[data-target="#modal-view"]').index(link);

        $('#carousel').carousel(index);
    });
});
