<?php 
 
$indexFile = "../index.php";
$configFolder = "../application/config";
$configFile = "../application/config/config.php";
$dbFile = "../application/config/database.php"; 

session_start();

    $step = isset($_GET['step']) ? $_GET['step'] : '';  
    switch ($step) {
        default: ?>
              <div class="panel-group">
                <div class="panel panel-default">
                  <div class="panel-heading">
                            <ul class="list">
                                <li class="active pk">Env. Check</li>
                                <li>Verification</li>
                                <li>DB Config</li>
                                <li>Site Config</li>
                                <li class="last">Complete!</li>
                            </ul>
                  </div>
                  <div class="panel-body">
                            <h3 class="text-center padding_70">Server Environment Checklist</h3>
                            <?php
                            $error = FALSE;
                            if (!is_writeable($indexFile)) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Index File (index.php) is not write able!</div>";
                            }
                            if (!is_writeable($configFolder)) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Config Folder (application/config/) is not write able!</div>";
                            }
                            if (!is_writeable($configFile)) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Config File (application/config/config.php) is not write able!</div>";
                            }
                            if (!is_writeable($dbFile)) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Database File (application/config/database.php) is not writable!</div>";
                            }
                            if (phpversion() < "7.0") {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Your PHP version is ".phpversion()."! PHP 7.0 or higher required!</div>";
                            } else {
                                echo "<div class='alert alert-success'><i class='icon-ok'></i> You are running PHP ".phpversion()."</div>";
                            } 
                            if (!extension_loaded('mysqli')) {
                                $error = TRUE;
                                echo "<div class='alert alert-error'><i class='icon-remove'></i> Mysqli PHP extension missing!</div>";
                            } else {
                                echo "<div class='alert alert-success'><i class='icon-ok'></i> Mysqli PHP extension loaded!</div>";
                            } 
                            if (!extension_loaded('curl')) {
                                $error = TRUE;
                                echo "<div class='alert alert-error'><i class='icon-remove'></i> CURL PHP extension missing!</div>";
                            } else {
                                echo "<div class='alert alert-success'><i class='icon-ok'></i> CURL PHP extension loaded!</div>";
                            } 
                            ?>
                            <div class="bottom">
                                <?php if ($error) { ?>
                                <a href="#" class="btn btn-primary button_1">Next</a>
                                <?php } else { ?>
                                <a href="index.php?step=0" class="btn btn-primary button_1">Next</a>
                                <?php } ?>
                            </div>
                  </div>
                </div>

        <?php
        break;
        case "0": ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">Verification</li>
                            <li>DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
                    <h3 style="margin-left:25px; margin-top: 10px; text-align: center;">Verify your purchase</h3>  
        <?php
        if ($_POST) { 
            
            $purchase_code = $_POST["purchase_code"];
            $username = $_POST["username"];
            $owner = $_POST["owner"];
			//need to change
            $source = 'CodeCanyon'; 
			//need to change
            $product_id = '23033741'; 

            $curl_handle = curl_init();
            //need to change
            curl_setopt($curl_handle, CURLOPT_URL, 'http://doorsoft.co/dsl/Validation/Validate/');
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
            $referer = "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24);
            $path = substr(realpath(dirname(__FILE__)), 0, -8);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
                'username' => $username,
                'purchase_code' => $purchase_code,
                'source' => $source,  
                'product_id' => $product_id,  
                'owner' => $owner,  
                'ip' => $_SERVER['REMOTE_ADDR'],
                'referer' => $referer,
                'path' => $path
                ));

            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
            if (! (is_object(json_decode($buffer)))) { 
                $cfc = strip_tags($buffer);
            } else {
                $cfc = NULL;
            }

            $object = json_decode($buffer);  

            if ($object->status == 'success') {
                ?>
                <form action="index.php?step=1" method="POST" class="form-horizontal"> 
                    <div class="alert alert-success"><i class='icon-ok'></i> <strong><?php echo ucfirst($object->status); ?></strong>:<br /><?php echo $object->message; ?></div> 
                    <input id="purchase_code" type="hidden" name="purchase_code" value="<?php echo $purchase_code; 
                    ?>" /> 
                    <input id="username" type="hidden" name="username" value="<?php echo $username; 
                    ?>" />
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Next"/>
                    </div> 
                </form>
                <?php
            } else {
                ?> 
                <?php 
                    
                    echo "<div class='alert alert-error'><i class='icon-remove'></i>". $object->message."</div>"; 
                ?>
                <form action="index.php?step=0" method="POST" class="form-horizontal">
                    <div class="control-group" style="margin-left:25px;">
                        <label class="control-label" for="username">Username</label>
                        <div class="controls">
                            <input style="width: 95%;" id="username" type="text" name="username" class="input-large" required="required" data-error="Username is required" placeholder="Username" />
                        </div>  
                    </div>
                    <div class="control-group" style="margin-left:25px;">
                        <label class="control-label" for="purchase_code">Purchase Code</label>
                        <div class="controls">
                            <input style="width: 95%;" id="purchase_code" type="text" name="purchase_code" class="input-large" required="required" data-error="Purchase Code is required" placeholder="Purchase Code" />
                        </div> 
                        <!-- modified -->
                        <input id="owner" type="hidden" name="owner" class="input-large" value="doorsoftco"  />
                    </div>
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Verify"/>
                    </div> 
                </form>
                <?php
            }
        } else {
            ?> 
            <p style="text-align: center;">Please provide your purchase information </p>
            <form action="index.php?step=0" method="POST" class="form-horizontal">
                <div class="control-group" style="margin-left:25px;margin-right:25px;">
                    <label class="control-label" for="username">Username</label>
                    <div class="controls">
                        <input style="width: 100%;" id="username" type="text" name="username" class="input-large" required="required" data-error="Username is required" placeholder="Username" />
                    </div> 
                </div>
                <div class="control-group" style="margin-left:25px;margin-right:25px;">
                    <label class="control-label" for="purchase_code">Purchase Code</label>
                    <div class="controls">
                        <input style="width: 100%;" id="purchase_code" required="required" type="text" name="purchase_code" class="input-large "  data-error="Purchase Code is required" placeholder="Purchase Code" />
                    </div>
                    <!-- modified -->
                    <input id="owner" type="hidden" name="owner" class="input-large" value="doorsoftco"  />
                </div>

                <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                    <input type="submit" class="btn btn-primary button_1"  value="Verify"/>
                </div> 
            </form>
            
                </div>
                </div>
                </div>
            <?php
        }
        break;
        case "1": ?>

        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">Verification</li>
                            <li>DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
                    
        <?php
        if ($_POST) {
            ?> 
            <h3 style="margin-left:25px; margin-top: 10px; text-align: center;">Database Configuration</h3> 
            <p style="margin-left:25px;">Please create a database in your server. And enter the db information here.</p>
            <form action="index.php?step=2" method="POST" class="form-horizontal">
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_hostname">Database Host</label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_hostname" type="text" name="db_hostname" class="input-large" required data-error="DB Host is required" placeholder="DB Host" value="localhost" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_username">Database Username</label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_username" type="text" name="db_username" class="input-large" autocomplete="off" required data-error="DB Username is required" placeholder="DB Username" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_password">Database Password</a></label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_password" type="password" name="db_password" class="input-large" autocomplete="off" data-error="DB Password is required" placeholder="DB Password" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_name">Database Name</label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_name" type="text" name="db_name" class="input-large" autocomplete="off" required data-error="DB Name is required" placeholder="DB Name" />
                    </div>
                </div>
                <input id="purchase_code" type="hidden" name="purchase_code" value="<?php echo $_POST['purchase_code']; ?>" />
                <input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" /> 
                <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                    <input type="submit" class="btn btn-primary button_1"  value="Next"/>
                </div>
                </div>
                </div>
                </div>
            </form>
            <?php
        }
        break;
        case "2":
        ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">Verification</li>
                            <li>DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
                <h3 style="text-align: center;">Saving database config</h3>
        <?php
        if ($_POST) {
            $db_hostname = $_POST["db_hostname"];
            $db_username = $_POST["db_username"];
            $db_password = $_POST["db_password"];
            $db_name = $_POST["db_name"];
            $purchase_code = $_POST["purchase_code"];
            $username = $_POST["username"];
            $link = new mysqli($db_hostname, $db_username, $db_password);
            if (mysqli_connect_errno()) {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Could not connect to MYSQL!</div>";
            } else {
                echo '<div class="alert alert-success"><i class="icon-ok"></i> Connection to MYSQL successful!</div>';
                $db_selected = mysqli_select_db($link, $db_name);
                if (!$db_selected) {
                    if (!mysqli_query($link, "CREATE DATABASE IF NOT EXISTS `$db_name`")) {
                        echo "<div class='alert alert-error'><i class='icon-remove'></i> Database " . $db_name . " does not exist and could not be created. Please create the Database manually and retry this step.</div>";
                        return FALSE;
                    } else {
                        echo "<div class='alert alert-success'><i class='icon-ok'></i> Database " . $db_name . " created</div>";
                    }
                }
                mysqli_select_db($link, $db_name);

                require_once('includes/core_class.php');
                $core = new Core();
                $dbdata = array(
                    'db_hostname' => $db_hostname,
                    'db_username' => $db_username,
                    'db_password' => $db_password,
                    'db_name' => $db_name
                    );

                if ($core->write_database($dbdata) == false) {
                    echo "<div class='alert alert-error'><i class='icon-remove'></i> Failed to write database details to ".$dbFile."</div>";
                } else {
                    echo "<div class='alert alert-success'><i class='icon-ok'></i> Database config written to the database file.</div>";
                }

            }
        } else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nothing to do...</div>"; }
        ?>
        <div class="bottom">
            <form action="index.php?step=1" method="POST" class="form-horizontal">
                <input id="purchase_code" type="hidden" name="purchase_code" value="<?php echo $_POST['purchase_code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                    <input type="submit" class="btn btn-primary button_1"  value="Previous"/> 
                </div>
            </form>
            <form action="index.php?step=3" method="POST" class="form-horizontal">
                <input id="purchase_code" type="hidden" name="purchase_code" value="<?php echo $_POST['purchase_code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                 
                <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                    <input type="submit" class="btn btn-primary button_1"  value="Next"/> 
                </div>
            </form>
            <br clear="all">
        </div>
        </div>
        </div>
        </div>
        <?php
        break;
        case "3":
        ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">Verification</li>
                            <li>DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
        <h3 style="margin-left:25px; text-align: center;">Site Config</h3>
        <?php
        if ($_POST) {
            ?>
            <form action="index.php?step=4" method="POST" class="form-horizontal">
                <div class="control-group" style="margin-left:25px; margin-right: 25px;">
                    <label class="control-label" for="installation_url">Installation URL</label>
                    <div class="controls">
                        <input style="width: 100%;" type="text" id="installation_url" name="installation_url" class="xlarge" required data-error="Installation URL is required" value="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24); ?>" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right: 25px;">
                    <label class="control-label" for="Encryption Key">Encryption Key</label>
                    <div class="controls"> 
                        <input style="width: 100%;" type="text" id="enckey" name="enckey" class="xlarge" required data-error="Encryption Key is required" value="" />
                    </div>
                </div> 
                <input type="hidden" name="purchase_code" value="<?php echo $_POST['purchase_code']; ?>" />
                <input type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <div class="bottom">
                    <a href="index.php?step=2" class="btn btn-primary button_1">Previous</a> 
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Next"/> 
                    </div>
                </div>
            </form>
                </div>
                </div>
                </div>

            <?php
        }
        break;
        case "4":
        ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">Verification</li>
                            <li>DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
        <h3 style="margin-left:25px; text-align: center;">Saving site config</h3>
        <?php
        if ($_POST) {
            $installation_url = $_POST['installation_url'];

            $enckey = $_POST['enckey']; 
            $purchase_code = $_POST["purchase_code"];
            $username = $_POST["username"];

            require_once('includes/core_class.php');
            $core = new Core();

            if ($core->write_config($installation_url, $enckey) == false) {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Failed to write config details to ".$configFile."</div>"; 
            } else { 
                echo "<div class='alert alert-success'><i class='icon-ok'></i> Config details written to the config file.</div>";
            }

        } else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nothing to do...</div>"; }
        ?>
        <div class="bottom">
            <form action="index.php?step=2" method="POST" class="form-horizontal">
                <input id="purchase_code" type="hidden" name="purchase_code" value="<?php echo $_POST['purchase_code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <input id="installation_url" type="hidden" name="installation_url" value="<?php echo $_POST['installation_url']; ?>" />
                <div class="bottom"> 
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Previous"/> 
                    </div>
                </div>
            </form>
            <form action="index.php?step=5" method="POST" class="form-horizontal">
                <!-- modified -->
                <input id="owner" type="hidden" name="owner" class="input-large" value="doorsoftco"  />
                <input id="purchase_code" type="hidden" name="purchase_code" value="<?php echo $_POST['purchase_code']; ?>" />
                <input id="username" type="hidden" name="username" value="<?php echo $_POST['username']; ?>" />
                <div class="bottom"> 
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Next"/> 
                    </div>
                </div>
            </form>
            <br clear="all">
        </div>
        </div>
        </div>
        </div>

        <?php
        break;
        case "5": ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">Verification</li>
                            <li>DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">

        <?php
        if ($_POST) {

            $owner = $_POST["owner"]; 
            $username = $_POST["username"];
            $purchase_code = $_POST["purchase_code"];
            //need to change
            $source = 'CodeCanyon'; 
            //need to change
            $product_id = '23033741';

            $installation_url = "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24);
            $installation_date_and_time = date('Y-m-d h:i:s');
 
            define("BASEPATH", "install/");
            include("../application/config/database.php"); 
            $curl_handle = curl_init(); 

            //need to change
            curl_setopt($curl_handle, CURLOPT_URL, 'http://doorsoft.co/dsl/Validation/Install/');
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
                'owner' => $owner,
                'username' => $username,
                'purchase_code' => $purchase_code,
                'source' => $source,
                'product_id' => $product_id,
                'installation_url' => $installation_url,
                'installation_date_and_time' => $installation_date_and_time
                ));
            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);  
            $object = json_decode($buffer);

            if ($object->status == 'success') {
                $dbdata = array(
                    'hostname' => $db['default']['hostname'],
                    'username' => $db['default']['username'],
                    'password' => $db['default']['password'],
                    'database' => $db['default']['database'],
                    'dbtables' => $object->database
                    );
                require_once('includes/database_class.php');
                $database = new Database();
                if ($database->create_tables($dbdata) == false) {
                    $finished = FALSE;
                    echo "<div class='alert alert-warning'><i class='icon-warning'></i> The database tables could not be created, please try again.</div>";
                } else {
                    $finished = TRUE;
                    require_once('includes/core_class.php');
                    $core = new Core();  
                    $core->create_rest_api(); 
                    //need to change
                    $core->create_rest_api_UV(); 
                    //need to change
                    $core->create_rest_api_I($username, $purchase_code, $installation_url); 
                }

            } else {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Error while validating your purchase code!</div>";
            }

        }
        if ($finished) {
            ?>

            <h3 style="margin-left:25px; text-align: center; margin-top: 10px"><i class='icon-ok'></i> Installation completed!</h3>
            <div style="margin-left:25px; margin-right: 50px; width: 90%; padding: 10px; border-radius: 5px; border: 1px solid #b5d6f6;">Please login now using the following credential:<br /><br />
                Email Address: <span style="font-weight:bold; letter-spacing:1px;">admin@doorsoft.co</span><br />Password: <span style="font-weight:bold; letter-spacing:1px;">123456</span><br /><br />
            </div> 
            <div style="margin-left:25px; margin-right: 50px; margin-top: 10px; width: 90%; padding: 10px; border-radius: 5px; border: 1px solid #b5d6f6;">Please change your credentials after login.
            </div>
            <div class="bottom"> 
                <div class="bottom" style="width: 100%;margin-top: 10px;"> 
                    <a href="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24); ?>" class="btn btn-primary button_1">Go to Login Page</a>
                </div>
            </div>
            </div>
            </div>
            </div>

            <?php
        }
    } 
?> 