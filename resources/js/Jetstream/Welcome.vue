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
                    <tr>
                    <td class="border px-4 py-2"></td>
                    <td class="border px-4 py-2"></td>
                    <td class="border px-4 py-2"></td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import JetApplicationLogo from './../Jetstream/ApplicationLogo'

    export default {
        data()
        {
            return {
                latitude: undefined,
                longitude: undefined,
                users: {}
            }
        },
        components: {
            JetApplicationLogo,
        },
        created()
        {
            this.getNearUsers();
        },
        methods:{
            getNearUsers()
            {
                const url = `/users/nearby?lat=${this.latitude}&long=${this.longitude}`
                axios.get(url).then(response => {
                    if (response.data) {
                        this.users = response.data;
                    }
                })
            }
        }
    }
</script>
