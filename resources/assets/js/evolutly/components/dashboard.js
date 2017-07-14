Vue.component('dashboard', {
    props: ['guard', 'tenant', 'user', 'projects' ,'clients'],
    data () {
        return {
            campaigns: [],
            projectForm: new EvolutlyForm({project_name: '', client_id: ''}),
            styling: {
                clearBottom: false,
            }
        }
    },

    mounted() {

    },
    computed: {
        hasNoProject(){
            if(this.projects.length < 4){
                this.styling.clearBottom = true
            }
        }
    },

    methods: { 
        projectChunks(projects) {
            return _.chunk(projects, 3)
        },
        campaignProgress(campaign)
        {
            if(campaign.total_points > 0)
            {
                return Math.floor((campaign.done_points / campaign.total_points) * 100);
            }
            return 0;
        },
        createProject()
        {
            if(this.guard === 'web')
            {
                let location = '/dashboard/projects/create'
                let url = `${window.location.protocol}//${this.tenant.username}.${Evolutly.domain}${location}`
                axios.post(url, this.projectForm)
                .then(function (response) {
                    this.$modal.hide('add-project');
                    this.projects.push(response.data.project)
                    this.projectForm.project_name = ''
                    this.projectForm.client_id = ''
                    this.$popup({ message: response.data.message, backgroundColor: '#4db6ac', delay: 5, color: '#ffc107', })
                }.bind(this))
                .catch(error => {
                    this.$popup({ message: 'Name is required' })
                })
            }else{
                this.$popup({ message: 'Oops Cant Do That!' })
            }
        },
        deleteProject(index,id) {
            var self = this
            if (this.guard === 'web') {
                axios.post('/dashboard/projects/' + id + '/delete')
                    .then(function (response) {
                        self.$popup({ message: response.data.message, backgroundColor: '#4db6ac', delay: 5, color: '#ffc107', })
                        self.projects.splice(index, 1)
                        self.projectForm.project_name = response.data.project
                        self.projectForm.project_name = ''
                    })
                    .catch(error => {
                        self.$popup({ message: _.first(error.response.data.campaign_name) })
                    })
            } else {
                self.$popup({ message: 'Oops Cant Do That!' })
            }
        },
        viewProject(id) {
            let location = '/dashboard/projects/'+id
            if (this.guard === 'employee') {
                location = '/employee/dashboard/projects/'+id
            }
            let url = `${window.location.protocol}//${this.tenant.username}.${Evolutly.domain}${location}`
            console.log(url)
            window.location.href = url
        },
        viewProgress(id,name) {
            let location = '/dashboard/projects/' + id + '/progress'
            if (this.guard === 'employee') {
                location = '/employee/dashboard/projects/'+id + '/progress'
            }
            let url = `${window.location.protocol}//${this.tenant.username}.${Evolutly.domain}${location}`
            this.campaigns = [];
            // load a loader circle
            axios.post(url).then(function (response) {
                this.campaigns = response.data
                this.$modal.show(name);
            }.bind(this))
        },
        show(name) {
            this.$modal.show(name);
        },
        hide(name) {
            this.$modal.hide(name);
        }
    },
});
