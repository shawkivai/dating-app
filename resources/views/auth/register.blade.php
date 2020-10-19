<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
         <div class="row">
        
            <!-- <x-jet-authentication-card-logo /> -->
            <h1> Dating Application</h1>
         </div>
            
        </x-slot>
        <x-jet-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>
            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>
            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
            <div class="mt-4">
                <x-jet-label for="date_of_birth" value="{{ __('Date OF Birth') }}" />
                <input class="date form-control" type="date" name="date_of_birth">
                <input type="hidden" id="latitude"  name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            </div>
            <div class="mt-4">
                <x-jet-label for="gender" value="{{ __('Gender') }}" />
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline1" name="gender" value="male" class="custom-control-input">
                    <label class="custom-control-label" for="customRadioInline1">Male</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline2" name="gender" value="female" class="custom-control-input">
                    <label class="custom-control-label" for="customRadioInline2">Female</label>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
<script type="text/javascript">
        var latitude = 0;
        var longitude = 0;
        var apiGeolocationSuccess = function (position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
            //alert("API geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
        };
        var tryAPIGeolocation = function () {
            // post("https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyDCa1LUe1vOczX1hO_iGYgyo8p_jYuGOPU", function (success) {
            //     apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
            // })
            //     .fail(function (err) {
            //         alert("API Geolocation error! \n\n" + err);
            //     });
        };
        var browserGeolocationSuccess = function (position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
            //alert("Browser geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
        };
        var browserGeolocationFail = function (error) {
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
            switch (error.code) {
                case error.TIMEOUT:
                    alert("Browser geolocation error !\n\nTimeout.");
                    break;
                case error.PERMISSION_DENIED:
                    if (error.message.indexOf("Only secure origins are allowed") == 0) {
                        tryAPIGeolocation();
                    }
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Browser geolocation error !\n\nPosition unavailable.");
                    break;
            }
        };
        var tryGeolocation = function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    browserGeolocationSuccess,
                    browserGeolocationFail,
                    {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
            }
        };
        tryGeolocation();
</script>
