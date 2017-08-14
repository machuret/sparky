@push('critical_css')
<style>
    div.dropdown-toggle::before {
        display:none;
    }
</style>
@endpush
<modal name="add-project" :width="500" :height="450" draggable=".window-header">
    <div class="panel widget-box">
        <div class="heading">
            <div class="title window-header">Create New Client <span @click="hide('add-project')" class="icon fa  fa-remove fg-red" style="font-size: 3.3em; position:absolute; top:0px; right:0px; margin-top:-13px;"></span></div>
        </div>
        <div class="content">
            <div class="row">
                    @if($guard === 'web')
                    <div class="input-control text full-size">
                    <form @submit.prevent="createProject()" role="form" class="padding10">
                        {{ csrf_field() }}
                        <input type="text" placeholder="Describe Your Client?" v-model="projectForm.client_name">
                        </br>
                        </br>
                        <div  class="row" style="padding:5px;margin-top:15px;">
                            <div class="input-control text full-size">
                                <label class="switch">
                                    <input type="checkbox" v-model="projectForm.newclient">
                                    <span class="check"></span>
                                    <span class="caption" v-if="!projectForm.newclient">New Client?</span>
                                    <span class="caption" v-else>Create New Client</span>
                                </label>
                            </div>
                        </div>
                        <div class="row" style="padding:5px;" v-if="projectForm.newclient">
                            <div class="input-control text full-size">
                                <span class="prepend-icon mif-user fg-blue"></span>
                                <input type="text" placeholder="Client Name" v-model="projectForm.user_name">
                                <span class="fg-red" v-show="projectForm.errors.has('user_name')">
                                @{{ projectForm.errors.get('user_name') }}
                            </span>
                            </div>
                        </div>
                        <div class="row" style="padding:5px;" v-if="projectForm.newclient">
                            <div class="input-control text full-size">
                                <span class="prepend-icon mif-mail fg-blue"></span>
                                <input type="text" placeholder="Client Email" v-model="projectForm.user_email">
                                <span class="fg-red" v-show="projectForm.errors.has('user_email')">
                                @{{ projectForm.errors.get('user_email') }}
                            </span>
                            </div>
                        </div>
                        <div class="row" style="padding:5px;" v-if="projectForm.newclient">
                            <div class="input-control text full-size">
                                <span class="prepend-icon mif-spell-check fg-blue"></span>
                                <input type="text" placeholder="Client Password" v-model="projectForm.user_password">
                                <span class="fg-red" v-show="projectForm.errors.has('user_password')">
                                @{{ projectForm.errors.get('user_password') }}
                            </span>
                            </div>
                        </div>
                        <v-select max-height="200px" v-if="clients.length > 0 && projectForm.newclient == false" v-model="projectForm.client_id" label="name" :options="clients"  placeholder="Pick an Existing Client"></v-select>
                        <!-- we need to easily create a client without an email or had a default email -->
                        <button type="submit" class="button info place-right" style="position:absolute;top:64px;right: 12px; height:36px;">
                                <span class="icon mif-keyboard-return"> Submit</span>
                        </button>
                    </form>
                    </div>
                    @endif
            </div>
        </div>
    </div>
</modal>