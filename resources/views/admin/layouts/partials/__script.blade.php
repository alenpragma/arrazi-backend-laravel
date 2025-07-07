	<!-- Fonts and icons -->
	<script src="{{asset('assets/admin/js/plugin/webfont/webfont.min.js')}}"></script>
	<script>
		WebFont.load({
			google: {"families":["Public Sans:300,400,500,600,700"]},
			custom: {"families":["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['{{asset('assets/admin/css/fonts.min.css')}}']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script> --}}




    <!--   Core JS Files   -->
	<script src="{{asset('assets/admin/js/core/jquery-3.7.1.min.js')}}"></script>
	<script src="{{asset('assets/admin/js/core/popper.min.js')}}"></script>
	<script src="{{asset('assets/admin/js/core/bootstrap.min.js')}}"></script>

	<!-- jQuery Scrollbar -->
	<script src="{{asset('assets/admin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

	<!-- Chart JS -->
	<script src="{{asset('assets/admin/js/plugin/chart.js/chart.min.js')}}"></script>

	<!-- jQuery Sparkline -->
	<script src="{{asset('assets/admin/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

	<!-- Chart Circle -->
	<script src="{{asset('assets/admin/js/plugin/chart-circle/circles.min.js')}}"></script>

	<!-- Datatables -->
	<script src="{{asset('assets/admin/js/plugin/datatables/datatables.min.js')}}"></script>

	<!-- Bootstrap Notify -->
	<script src="{{asset('assets/admin/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

	<!-- jQuery Vector Maps -->
	<script src="{{asset('assets/admin/js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
	<script src="{{asset('assets/admin/js/plugin/jsvectormap/world.js')}}"></script>

	<!-- Sweet Alert -->
	<script src="{{asset('assets/admin/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

	<!-- Kaiadmin JS -->
	<script src="{{asset('assets/admin/js/kaiadmin.min.js')}}"></script>

	<!-- Kaiadmin DEMO methods, don't include it in your project! -->
	<script src="{{asset('assets/admin/js/setting-demo.js')}}"></script>
	{{-- <script src="{{asset('assets/admin/js/demo.js')}}"></script> --}}
	<script>
		$('#lineChart').sparkline([102,109,120,99,110,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#177dff',
			fillColor: 'rgba(23, 125, 255, 0.14)'
		});

		$('#lineChart2').sparkline([99,125,122,105,110,124,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#f3545d',
			fillColor: 'rgba(243, 84, 93, .14)'
		});

		$('#lineChart3').sparkline([105,103,123,100,95,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#ffa534',
			fillColor: 'rgba(255, 165, 52, .14)'
		});
	</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.updateStatusBtn').forEach(button => {
            button.addEventListener('click', function () {
                let userId = this.dataset.id;
                let userName = this.dataset.name;
                let userUsername = this.dataset.username;
                let blockStatus = this.dataset.block;
                let walletStatus = this.dataset.wallet;

                document.getElementById('action_user_id').value = userId;
                document.getElementById('action_user_name').value = userName;
                document.getElementById('action_user_username').value = userUsername;
                document.getElementById('action_block_status').value = blockStatus;
                document.getElementById('action_wallet_status').value = walletStatus;
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.updateStatusBtn2').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                const userName = this.dataset.name;
                const userUsername = this.dataset.username;

                document.getElementById('message_user_id').value = userId;
                document.getElementById('message_user_name').value = userName;
                document.getElementById('message_user_username').value = userUsername;
            });
        });
    });
</script>



<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'insertImage', 'blockQuote', '|',
                        'undo', 'redo'
                    ]
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                image: {
                    toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side'],
                    styles: ['full', 'side']
                },
                ckfinder: {
                    // Uncomment this and add a server-side route if you want real upload:
                    // uploadUrl: '/upload/image?_token={{ csrf_token() }}'
                }
            })
            .catch(error => {
                console.error('Editor error:', error);
            });
    });
</script>

