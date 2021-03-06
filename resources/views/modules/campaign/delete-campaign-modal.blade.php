<modal :name="`delete-campaign-modal-${campaign.id}`" :width="500" :height="250" draggable=".window-header">
    <div class="panel widget-box">

        <div class="heading" style="background-color:#4db6ac;">
            <div class="title window-header align-center">Confirm Campaign Deletion </div>
        </div>

        <div class="content">

                <div class="text align-center">
                        <span class="icon mif-warning fg-amber" style="font-size: 5em;"></span> 
                        <h3 class="fg-amber">This Process is Irreversible</h3>                        
                </div>
        </div>

    </div>
    <div class="row" style="position: absolute;width: 100%;bottom:0;">
        <button @click="deleteCampaign(campaign)" type="button" class="button fg-white" :class="{'bg-amber': !campaignForm.busy,'bg-red': campaignForm.busy}" style="width:500px; margin-bottom:-1px;" :disabled="campaignForm.busy">
            <strong class="icon mif-bin" v-if="!campaignForm.busy"> Confirm</strong>
            <span class="icon mif-spinner mif-ani-spin" v-else></span>
        </button>
        <button @click="closeDeleteCampaignModal()" type="button" class="button fg-white" style="width:500px; margin-bottom:-1px;background-color:#4db6ac;">
            <strong class="icon mif-not"> Cancel</strong>
        </button>
    </div>
</modal>