<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{ config('app.name') }}</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'>
	<meta name="viewport" content="width=device-width">

	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ mix('/css/renewals/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/renewals/material-bootstrap-wizard.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/renewals/demo.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/renewals/individual.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
</head>

<body>
    <div class="image-container set-full-height"
        style="background-color: #dddddd;"
    >
		<a class="hidden-xs">
			<div class="logo-container">
				<div class="logo">
                    <img src="{{ asset('images/renewals/logo.png') }}">
				</div>
			</div>
		</a>

		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<p class="text-center device-notice renewal-notice">
						We recommend completing your renewal on a desktop or tablet device.
					</p>

					<div id="internet-explorer-notice" class="text-center renewal-notice hidden">
						<h3>WARNING</h3>
						<p>We noticed you are using Internet Explorer. We recommend using Edge, Chrome or Firefox to complete your renewal.</p>
					</div>

					<div class="wizard-container">
                        @if ($errors->all())
                            <ul class="alert alert-danger list-group">
                                @foreach ($errors->all() as $message)
                                    <li class="pl-5">{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif

						<div class="card wizard-card" data-color="red" id="wizard">
							@yield('content')
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="footer">
            <div class="container text-center"></div>
        </div>
    </div>

    <script src="{{ mix('/js/renewals/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ mix('/js/renewals/bootstrap.min.js') }}"></script>
    <script src="{{ mix('/js/renewals/jquery.bootstrap.js') }}"></script>
    <script src="{{ mix('/js/renewals/material-bootstrap-wizard.js') }}"></script>
    <script src="{{ mix('/js/renewals/jquery.validate.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>

	<script>
		$(document).ready(function() {
			if (isInternetExplorer()) {
				$('#internet-explorer-notice').removeClass('hidden');
				$('.device-notice').addClass('hidden');
			}
		});

        function copyObject(object) {
            return Object.keys(object).map(function(e) {
                return object[e];
            })
        }

        function isInternetExplorer() {
			ua = navigator.userAgent;
			/* MSIE used to detect old browsers and Trident used to newer ones*/
			var is_ie = ua.indexOf("MSIE ") > -1 || ua.indexOf("Trident/") > -1;

			return is_ie;
		}
	</script>

	@if (isset($stripe))
		<script>
			var stripeHandler = StripeCheckout.configure({
				key: "{{ config('services.stripe.key') }}",
				image: "",
				locale: 'auto',
				name: "{{ config('app.name') }}",
				closed: function() {
					$('.btn-finish').attr('disabled', false);
				},
				token: function(token) {
					$('input[name=stripe_token]').val(token.id);
                    totalSubmitted++;
					$('#individual-renewal-form').submit();
				}
			});
		</script>
	@endif

	@stack('scripts')
</body>
</html>
