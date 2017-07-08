/**
 * Export the root Evolutly application.
 */
module.exports = {
    el: '#evolutly-app',


    /**
     * Initial FrontEnd State We Should Add this On The Backend
     */

    data: {
        user: Evolutly.state.user,
        tenant: Evolutly.state.tenant,
        projects: (Evolutly.state.projects ? Evolutly.state.projects : []),
        'current_project': (Evolutly.state.currentProject ? Evolutly.state.currentProject : null),
        'project_files': (Evolutly.state.currentProjectFiles ? Evolutly.state.currentProjectFiles : []),
        'project_workers': (Evolutly.state.currentProjectWorkers ? Evolutly.state.currentProjectWorkers : [])
    },


    /**
     * The component has been created by Vue.
     */
    created() {
        var self = this;
        
        Bus.$on('updateUserData', function () {
            self.getAuthUser();
        });

        Bus.$on('updateEmployeeData', function () {
            self.getAuthEmployee();
        });

        Bus.$on('updateClientData', function () {
            self.getAuthClient();
        });

    },


    /**
     * Prepare the application.
     */
    mounted() {
        this.whenReady();
    },


    methods: {
        /**
         * Finish bootstrapping the application.
         */
        whenReady() {
            //
        },


        /**
         * Load the data for an authenticated user.
         */
        loadDataForAuthenticatedUser() {
            this.getAuthUser();
        },
        loadDataForAuthenticatedEmployee() {
            this.getAuthEmployee();
        },
        loadDataForAuthenticatedClient() {
            this.getAuthClient();
        },


        /*
         * Get the current authenticated user
         */
        getAuthUser() {
            axios.get(this.apiDomain+'/auth/user')
                .then(response => {
                    this.user = response.data;
                });
        },

        /*
         * Get the current authenticated employee
         */
        getAuthEmployee() {
            axios.get(this.apiDomain +'/auth/employee')
                .then(response => {
                    this.user = response.data;
                });
        },

        /*
         * Get the current authenticated Client
         */
        getAuthClient() {
            axios.get(this.apiDomain +'/auth/client')
                .then(response => {
                    this.user = response.data;
                });
        },
    },
};
