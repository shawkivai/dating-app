<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div>
                <h1><b> Users Around 5 Kilometer</b></h1>
            </div>
        </div>

        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
             <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">

            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                    <th   class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User Name</th>
                    <th   class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th   class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Profile Picture</th>
                    <th   class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gender</th>
                    <th   class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> Age </th>
                    <th   class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> Distance </th>
                    <th   class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"> Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td class="border px-4 py-2"> {{user.name}}</td>
                        <td class="border px-4 py-2"> {{user.email}}</td>
                        <td class="border px-4 py-2"> 
                            <img v-if="user.profile_pic" :src="user.profile_pic" style="height:105px">
                            <h3 v-else> No Profile Picture found</h3>
                        </td>
                        <td class="border px-4 py-2"> {{user.gender | unEnumify}}</td>
                        <td class="border px-4 py-2"> {{user.age}}</td>
                        <td class="border px-4 py-2"> {{user.distance}}</td>
                        <td class="border px-4 py-2">
                            <jet-button @click.native="likeUser(user.id)">
                                Like
                            </jet-button>
                             <jet-danger-button @click.native="dislikeUser(user.id)">
                                Dislike
                            </jet-danger-button>
                        </td>
                    </tr>

                    <tr v-if="users ? !users.length : false">
                        <td colspan="7" class="text-center"><b> No Users Found </b></td>
                    </tr>
                    
                </tbody>
            </table>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from './../../Jetstream/ApplicationLogo'
    import JetButton from './../../Jetstream/Button'
     import JetDangerButton from './../../Jetstream/DangerButton'
     import Swal from 'sweetalert2';
    export default {
        data()
        {
            return {
                latitude: undefined,
                longitude: undefined,
                users: [],
                looggedInUserId: undefined,
            }
        },
        components: {
            JetApplicationLogo,
            JetButton,
            JetDangerButton,
            Swal,
        },
        created()
        {
            
            this.getUserRealtimeLocation();
            this.getLoggedInUser();
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
            getLoggedInUser()
            {
                const url = `/loggedin/user`
                axios.get(url).then(response => {
                    if (response.data) {
                        this.looggedInUserId = response.data.id;
                    }
                })
            },
            dislikeUser(dislikeLikeUserId)
            {
                const url = `/api/user/${this.looggedInUserId}/dislike`
                const payload = {
                    user_id: this.looggedInUserId,
                    disliked_user_id: dislikeLikeUserId,
                    is_disliked: 1,
                    is_liked: 0,
                }
                axios.post(url, payload).then((response) => {
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    background: 'rgb(240,240,240)'
                });

                Toast.fire({
                    type: response.data == 'success' ? 'success' : 'error',
                    title:  response.data.message
                });

                })
            },
            likeUser(likedUserId)
            {
                const url = `/api/user/${this.looggedInUserId}/like`
                const payload = {
                    user_id: this.looggedInUserId,
                    liked_user_id: likedUserId,
                    is_liked: 1,
                    is_disliked: 0,
                }

                axios.post(url, payload).then((response) => {

                

                if(response.data.status === 'nutual_like') {
                        return Swal.fire({
                            title: '<strong>Congratulations</strong>',
                            icon: 'info',
                            html:
                                '<b>'+response.data.message+'</b>',
                            showCloseButton: true,
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText:
                                '<i class="fa fa-thumbs-up"></i> Great!',
                            confirmButtonAriaLabel: 'Thumbs up, great!',
                            
                            })
                } else {
                                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    background: 'rgb(240,240,240)'
                });
                return Toast.fire({
                            type:   response.data == 'success' ? 'success' : 'error',
                            title:  response.data.message
                        });
                }

                })

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
