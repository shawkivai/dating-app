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
                    <th class="px-4 py-2">Profile Picture</th>
                    <th class="px-4 py-2">Gender</th>
                    <th class="px-4 py-2"> Age </th>
                    <th class="px-4 py-2"> Distance </th>
                    <th class="px-4 py-2"> Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users">
                        <td class="border px-4 py-2"> {{user.name}}</td>
                        <td class="border px-4 py-2"> {{user.email}}</td>
                        <td class="border px-4 py-2"> 
                            <img :src="user.profile_pic" style="height:105px">

                        </td>
                        <td class="border px-4 py-2"> {{user.gender === 'm' ? 'Male' : 'Female'}}</td>
                        <td class="border px-4 py-2"> {{user.age}}</td>
                        <td class="border px-4 py-2"> {{user.distance}}</td>
                        <td class="border px-4 py-2">
                            <jet-button @click.native="likeUser">
                                Like
                            </jet-button>
                             <jet-danger-button @click.native="dislikeUser">
                                Dislike
                            </jet-danger-button>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from './../../Jetstream/ApplicationLogo'
    import JetButton from './../../Jetstream/Button'
     import JetDangerButton from './../../Jetstream/DangerButton'
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
            JetButton,
            JetDangerButton
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
            dislikeUser()
            {

            },
            likeUser()
            {

            },
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
                        self.latitude = 23.801738560316018;
                        self.longitude = 90.3796451525854;
                            console.log(error.message);
                            self.getNearUsers();
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
