<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div>
                <jet-application-logo class="block h-12 w-auto" />
            </div>

           
        </div>

        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <table class="table-auto">
                <thead>
                    <tr>
                    <th class="px-4 py-2">User Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Gender</th>
                    <th class="px-4 py-2">DOB</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users">
                        <td class="border px-4 py-2"> {{user.name}}</td>
                        <td class="border px-4 py-2"> {{user.email}}</td>
                        <td class="border px-4 py-2"> {{user.gender}}</td>
                        <td class="border px-4 py-2"> {{user.date_of_birth}}</td>
                        <td class="border px-4 py-2"> {{user.distance}}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from './../../Jetstream/ApplicationLogo'

    export default {
        data()
        {
            return {
                latitude: undefined,
                longitude: undefined,
                users: [],
            }
        },
        components: {
            JetApplicationLogo,
        },
        created()
        {
            
            this.getUserRealtimeLocation();
        },

        methods:{
            getNearUsers()
            {
                const url = `/users/nearby?latitude=${this.latitude}&longitude=${this.longitude}`
                axios.get(url).then(response => {
                    if (response.data) {
                        this.users = response.data;
                    }
                })
            },
            // getUserRealtimeLocation()
            // {

            //     var apiGeolocationSuccess = function (position) {
            //         latitude = position.coords.latitude;
            //         longitude = position.coords.longitude;
            //         document.getElementById('latitude').value = latitude;
            //         document.getElementById('longitude').value = longitude;
            //         //alert("API geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
            //     };
            //     var tryAPIGeolocation = function () {
            //         post("https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyDCa1LUe1vOczX1hO_iGYgyo8p_jYuGOPU", function (success) {
            //             apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
            //         })
            //             .fail(function (err) {
            //                 alert("API Geolocation error! \n\n" + err);
            //             });
            //     };
            //     var browserGeolocationSuccess = function (position) {
            //         latitude = position.coords.latitude;
            //         longitude = position.coords.longitude;
            //         document.getElementById('latitude').value = latitude;
            //         document.getElementById('longitude').value = longitude;
            //         //alert("Browser geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
            //     };
            //     var browserGeolocationFail = function (error) {
            //         document.getElementById('latitude').value = latitude;
            //         document.getElementById('longitude').value = longitude;
            //         switch (error.code) {
            //             case error.TIMEOUT:
            //                 alert("Browser geolocation error !\n\nTimeout.");
            //                 break;
            //             case error.PERMISSION_DENIED:
            //                 if (error.message.indexOf("Only secure origins are allowed") == 0) {
            //                     tryAPIGeolocation();
            //                 }
            //                 break;
            //             case error.POSITION_UNAVAILABLE:
            //                 alert("Browser geolocation error !\n\nPosition unavailable.");
            //                 break;
            //         }
            //     };
            //     var tryGeolocation = function () {
            //         if (navigator.geolocation) {
            //             navigator.geolocation.getCurrentPosition(
            //                 browserGeolocationSuccess,
            //                 browserGeolocationFail,
            //                 {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
            //         }
            //     };
            //     tryGeolocation();
            // },
            getUserRealtimeLocation()
            {
                if(navigator.geolocation) {
                    var self = this;
                    navigator.geolocation.getCurrentPosition(
                    function (position) {
                        // self.latitude = 23.801738560316018;
                        // self.longitude = 90.3796451525854;
                        self.latitude = position.coords.latitude;
                        self.longitude = position.coords.longitude;
                        console.log(position.coords.latitude)
                        console.log(position.coords.longitude);
                        self.getNearUsers();

                    },
                    function (error) {
                        // self.latitude = 23.801738560316018;
                        // self.longitude = 90.3796451525854;
                            console.log(error.message);
                            // self.getNearUsers();
                        }, {
                            maximumAge: 50000, timeout: 20000, enableHighAccuracy: true
                        }
                    );
                    
                } else {
                    console.log("Geolocation is not supported by this browser.");
                }
                console.log(this.latitude);
                
            }
            

        }
    }
</script>
