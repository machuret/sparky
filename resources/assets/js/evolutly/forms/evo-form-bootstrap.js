/**
 * You Can Extend Some Forms, to Add Extra Field Without Messing Up With Your Current Field
 *  Note: if You Declare Something Like On Your evolutly.js where we declare a new Vue Instance
 *  Added Inside evolytly.js (main) 
 *  Evolutly.forms.sampleForm = {
 *  currency: ''
 *  };
 *  the field is added to the existing new EvolutlyForm
 *  Add this inside your data() for props or data in the main vue instance
 *  Example:
 *  sampleForm: new EvolutlyForm({
 *               name: '',
 *               number: '',
 *               cvc: '',
 *               month: '',
 *               year: '',
 *           })
 *  Note: to module.exports the component that contains the form
 *  For Example We Declare sampleForm in our data() or data
 *  sampleForm: $.extend(true, new EvolutlyForm({
 *               invitation: null
 *              }), Spark.forms.register)
 *  
 */

Evolutly.forms = {
    projectForm: {
        project_name: '',
        client_id: ''
    },
    campaignForm: {
        campaign_name: '',
        campaign_order: 0
    },
    taskForm: {
        name: '',
        description: '',
        link: '',
        points: 1
    },
    subtaskForm:{
        name: '',
        points: '',
        priority: '',
        link: '',
        due_date: ''
    },
    commentForm: {
        title: '',
        body: ''
    },
    formBuilderForm: {
        title: '',
        body: ''
    },
    fileForm: {
        name: '',
        lastModified: '',
        lastModifiedDate: '',
        size: '',
        type: '',
        webkitRelativePath: ''
    }


};

/**
 * Load the Evolutly form helper class.
 */
// relative path set on our webpack.mix.js 
// resources/assets/js/evolutly
require('forms/evo-form');

/**
 * Define the Evolutly form Error collection class.
 */
// relative path set on our webpack.mix.js
// resources/assets/js/evolutly
require('forms/evo-errors');

/**
 * Add additional HTTP / form helpers to the Evolutly object.
 */
// relative path set on our webpack.mix.js
// resources/assets/js/evolutly
$.extend(Evolutly, require('forms/evo-http'));
