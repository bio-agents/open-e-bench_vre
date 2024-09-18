<!--<script src="https://cdn.jsdelivr.net/npm/vue"></script>-->

<?php

require __DIR__ . "/../../../config/bootstrap.php";

require('oeb_view_functions.php');
// project list
$projects = getProjects_byOwner();
$agentsList = getAgents_List();

sort($agentsList);

//get all files for user
$allFiles = getFilesToDisplay(array('_id' => $_SESSION['User']['dataDir']), null);

?>

<?php
require "../../htmlib/header.inc.php";
require "../../htmlib/js.inc.php";
?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-container-bg-solid page-sidebar-fixed">
    <div class="page-wrapper">
        <input type="hidden" id="base-url" value="<?php echo $GLOBALS['BASEURL']; ?>" />

        <?php require "../../htmlib/top.inc.php"; ?>
        <?php require "../../htmlib/menu.inc.php"; ?>

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">

                <!-- BEGIN PAGE HEADER-->


                <!-- BEGIN PAGE BAR -->
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <a href="home/">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>Results</span>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>Views</span>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>General</span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE BAR -->

                <!-- BEGIN PAGE TITLE-->
                <h1 class="page-title"> Results

                    <!-- Choose project from list of projects the user has in his workspace -->
                    <div class="input-group" style="float:right; width:200px; margin-right:10px;">
                        <span class="input-group-addon" style="background:#5e738b;"><i class="fa fa-sitemap font-white"></i></span>
                        <select class="form-control" id="select_project" onchange="loadProjectWS(this);">
                            <?php foreach ($projects as $p_id => $p) {
                                $selected = (($_SESSION['User']['dataDir'] == $p_id) ? "selected" : ""); ?>
                                <option value="<?php echo $p_id; ?>" <?php echo $selected; ?>><?php echo $p['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </h1>

                <!-- END PAGE TITLE -->
                <!-- END PAGE HEADER -->


                <!-- BEGIN EXAMPLE TABLE PORTLET -->

                <div class="row">
                    <div class="col-md-12 col-sm-12">

                        <div class="portlet light bordered">

                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon-share font-dark hide"></i>
                                    <span class="caption-subject font-dark bold uppercase">Select File(s)</span> <small style="font-size:75%;">Please select the file or files you want to use</small>
                                </div>
                                <div class="actions">
                                    <a href="<?php echo $GLOBALS['BASEURL']; ?>oeb_results/oeb_views/oeb_generalView.php" class="btn green"> Reload Workspace </a>
                                </div>
                            </div>

                            <div class="portlet-body">

                                <div class="input-group" style="margin-bottom:20px;">
                                    <span class="input-group-addon" style="background:#5e738b;"><i class="fa fa-wrench font-white"></i></span>
                                    <select id="agentSelector" class="form-control" style="width:100%;" onchange="loadWSAgent(this)">
                                        <!-- <option value="">Filter files by agent</option> -->
                                        <?php foreach ($agentsList as $tl) { ?>
                                            <option value="<?php echo $tl["_id"]; ?>" <?php if ($_REQUEST["agent"] == $tl["_id"]) echo 'selected'; ?>><?php echo $tl["name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>



                                </form>
                                <?php
                                $proj_name_active   = basename(getAttr_fromGSFileId($_SESSION['User']['dataDir'], "path"));
                                $file_filter = array(
                                    "agent"       => getSelectedAgent($agentsList, $_REQUEST["agent"]),
                                    "data_type" => "workflow_stats",
                                    "project"   => $proj_name_active
                                );
                                $filteredFiles = getGSFiles_filteredBy($file_filter);
                                ?>
                                <div>
                                    <div class="col-xs-12
                                     selectorLists">
                                        <ul class=" list-group">
                                            <span id="listOfAgents">
                                                <?php foreach ($filteredFiles as $key => $value) {
                                                    echo '<li class="list-group-item runs"><input class="checkboxes" type="checkbox" value="' . $value['parentDir'] . '"name="sport">  ' . basename(getAttr_fromGSFileId($value['parentDir'], "path")) . '</li>';
                                                }
                                                ?>
                                            </span>
                                        </ul>
                                    </div>




                                </div>
                                <button class=" btn green" onclick="getallselected2()" id="btn-run-files" style="margin-top:20px;">Run Selected Files</button>
                                <!-- <button class=" btn green" onclick="getallselected()" id="btn-run-files" style="margin-top:20px;">Run Selected Files</button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->









                <?php
                require "../../htmlib/footer.inc.php";

                ?>
                <style>
                    .selected {
                        background-color: #eef1f5;
                    }

                    .selectorLists {
                        min-height: 20vh;
                        min-width: 22vh;

                    }
                </style>
                <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
                <script>
                    var redirect_url = "oeb_results/oeb_views/oeb_generalView.php"

                    function loadProjectWS(id) {
                        location.href = baseURL + 'applib/oeb_manageProjects.php?op=reload&pr_id=' + id.value + '&redirect_url=' + redirect_url;
                    };

                    function loadWSAgent(op) {
                        console.log(op.value)
                        location.href = baseURL + redirect_url + "?agent=" + op.value;

                    }

                    new Sortable(listOfAgents, {
                        animation: 150,
                        ghostClass: 'blue-background-class'
                    });
                    // new Sortable(listOfAgents, {
                    //     group: 'runs', // set both lists to same group
                    //     multiDrag: true, // Enable multi-drag
                    //     selectedClass: 'selected', // The class applied to the selected items
                    //     fallbackTolerance: 3, // So that we can select items on mobile
                    //     animation: 50
                    // });

                    // new Sortable(listOfAgentsSelected, {
                    //     group: 'runs',
                    //     multiDrag: true, // Enable multi-drag
                    //     selectedClass: 'selected', // The class applied to the selected items
                    //     fallbackTolerance: 3, // So that we can select items on mobile
                    //     filter: '.head',
                    //     //animation: 50
                    // });

                    function getallselected2() {
                        var agent = $("#agentSelector option:selected").val();


                        var arrayofexecutions = [];
                        $.each($("input[name='sport']:checked"), function() {
                            arrayofexecutions.push($(this).val());
                        });
                        //console.log(arrayofexecutions, agent);
                        viewResults(arrayofexecutions, agent);
                    }

                    function getallselected() {
                        var agent = $("#agentSelector option:selected").val();


                        var arrayofexecutions = [];
                        $('ul#listOfAgentsSelected li').each(function(i) {
                            arrayofexecutions.push($(this).attr('data')); // This is your rel value)
                        });
                        //console.log(arrayofexecutions, agent);
                        viewResults(arrayofexecutions, agent);
                    }
                    viewResults = function(execution, agent) {
                        App.blockUI({
                            boxed: true,
                            message: 'Creating agent output, this operation may take a while, please don\'t close the tab...'
                        });
                        console.log("execution=" + execution + "&agent=" + agent);
                        $.ajax({
                            type: "POST",
                            url: baseURL + "/applib/loadOutput.php",
                            data: "execution=" + execution + "&agent=" + agent,
                            success: function(data) {

                                if (data == '1') {
                                    setTimeout(function() {
                                        location.href = 'agents/' + agent + '/output.php?execution=' + execution;
                                    }, 500);
                                } else if (data == '0') {
                                    setTimeout(function() {
                                        location.href = 'workspace/';
                                    }, 500);
                                }
                            }
                        });

                    };
                </script>
