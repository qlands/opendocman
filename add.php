<?php
/*
add.php - adds files to the repository
Copyright (C) 2007 Stephen Lawrence Jr.
Copyright (C) 2002-2012 Stephen Lawrence Jr.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.


							ADD.PHP DOCUMENTATION
This page will allow user to set rights to every department.  It uses javascript to handle client-side data-storing and data-swapping.  Each time the data is stored, it is stored onto an array of objects of class Deparments.  It is also stored onto hidden form field in the page for php to access since php and javascript do not communicate (server-side and client-side share different environment).
As the user choose a deparment from the drop box named dept_drop_box, loadData(_selectedIndex) function is invoked.
After the data is loaded for the chosen deparment, if the user changes the right setting (right radio button e.g. "view", "read")
setData(selected_rb_name) is invoked.  This function will set the data in the appropriate deparment[] and it will set the hidden field as wel.  The connection between hidden field and department[] is the hidden field's name and the deparment[].getName().  The department names in the array is populated with the correct department names from the database.  This will lead to problems.  There will be deparment names of more than one word eg. "Information Systems".  The hidden field's accessible name cannot be more than one word.  PHP cannot access multiple word variables.  Therefore, javascript spTo_(string) (space to underscore) will go through and subtitude all the spaces with the underscore character. */

session_start();

if (!isset($_SESSION['uid']))
{
    header('Location:index.php?redirection=' . urlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));
    exit;
}
include('odm-load.php');
include('udf_functions.php');
require_once("AccessLog_class.php");
require_once("File_class.php");

$user_obj = new User($_SESSION['uid'], $GLOBALS['connection'], DB_NAME);

//un_submitted form
if(!isset($_POST['submit'])) 
{
    $llast_message = (isset($_REQUEST['last_message']) ? $_REQUEST['last_message']:'');
    draw_header(msg('area_add_new_file'), $llast_message);
    echo '<table border="0" cellspacing="5" cellpadding="5">'."\n";
    //////////////////////////Get Current User's department id///////////////////
    $query ="SELECT department FROM {$GLOBALS['CONFIG']['db_prefix']}user where id='$_SESSION[uid]'";
    $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
    if(mysql_num_rows($result) != 1) /////////////If somehow this user belongs to many departments, then error out.

    {
        header('Location:error.php?ec=14');
        exit; //non-unique error
    }
    list($current_user_dept) = mysql_fetch_row($result);    
    $index = 0;
    ///////Define a class that hold Department information (id, name, and rights)/////////
    //this class will be used to temporarily hold department information client-side wise//
    ?>

<script type="text/javascript">

    //define a class like structure to hold multiple data
    function Department(name, id, rights)
    {
        this.name = name;
        this.id = id;
        this.rights = rights;
        this.isset_flag = false;
        if (typeof(_department_prototype_called) == "undefined")
        {
            _department_prototype_called = true;
            Department.prototype.getName = getName;
            Department.prototype.getId = getId;
            Department.prototype.getRights = getRights;
            Department.prototype.setName = setName;
            Department.prototype.setId = setId;
            Department.prototype.setRights = setRights;
            Department.prototype.issetFlag = issetFlag;
            Department.prototype.setFlag = setFlag;

        }
        function setFlag(set_boolean)
        {	this.isset_flag = set_boolean;	}

        function getName()
        {       return this.name;		}

        function getId()
        {       return this.id;	                }

        function getRights()
        {	return parseInt(this.rights);		}

        function setRights(rights)
        {       this.rights = parseInt(rights); }

        function setName(name)
        {       this.name = name;               }

        function setId(id)
        {       this.id = id;         }

        function issetFlag()
        {       return this.isset_flag;         }
    }

    ///Create default_Setting and all_Setting obj for mass department setting/////
    var default_Setting_pos = 0;
    var all_Setting_pos = 1;
    var departments = new Array();
    var default_Setting = new Department("<?php echo msg('label_default_for_unset')?>", "0", "0");
    var all_Setting = new Department("<?php echo msg('all')?>", "0", "0");
    departments[all_Setting_pos] = all_Setting;
    departments[default_Setting_pos] = default_Setting;
    /////////////////////////Populate Department obj////////////////////////////////
    <?php

    $allDepartments = Department::getAllDepartments();
    foreach ($allDepartments as $singleDepartment)
    {
        if($singleDepartment['id'] == $current_user_dept)
        {
            echo 'departments[' . ($index+2) . '] = new Department("' . $singleDepartment['name'] . '", "' . $singleDepartment['id'] . '", "1")' . "\n";
        }
        else
        {
            echo 'departments[' . ($index+2) . '] = new Department("' . $singleDepartment['name'] . '", "' . $singleDepartment['id'] . '", "0")' . "\n";
        }
        $index++;
    }
    ?>
</script>
<script type="text/javascript"src="functions.js"></script>
<!-- file upload formu using ENCTYPE -->
<form name="main" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" onsubmit="return checksec();">

        <?php
		//CHM
		$query = "SELECT table_name FROM {$GLOBALS['CONFIG']['db_prefix']}udf WHERE field_type = '4'";
		$result = mysql_query($query) or die ("Error in query163: $query. " . mysql_error());
		$num_rows = mysql_num_rows($result);
		$i=0;
		while($data = mysql_fetch_array($result)){
			$explode_v = explode('_', $data['table_name']);
			$t_name = $explode_v[2];
			?>
			<input type="hidden" id="secondary<?=$i?>" name="secondary<?=$i?>" value="" /> <!-- CHM hidden and onsubmit added-->
			<input type="hidden" id="tablename<?=$i?>" name="tablename<?=$i?>" value="<?=$t_name?>" /> <!-- CHM hidden and onsubmit added-->
		 <?php
		$i++; 
		}?>
      <input id="i_value" type="hidden" name="i_value" value="<?=$i?>" /> <!-- CHM hidden and onsubmit added-->
 
    <tr>
        <td>
            <a class="body" tabindex=1 href="help.html#Add_File_-_File_Location" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_file_location');?></a>
        </td>
        <td colspan=3>
            <input tabindex="0" name="file[]" type="file" multiple="multiple">
        </td>
    </tr>

<?php if($user_obj->isAdmin()) { // Begin Admin ?>
    <tr>

        <td>
            <?php echo msg('editpage_assign_owner');?>
        </td>
        <td>
            <select name="file_owner">
            <?php
                        // query to get a list of available users
                        $query = "SELECT id, last_name, first_name FROM {$GLOBALS['CONFIG']['db_prefix']}user ORDER BY last_name";
                        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
                        //////////////////Forbidden////////////////////
                        while(list($id, $last_name, $first_name) = mysql_fetch_row($result))
                        {
                            if($id == $_SESSION['uid'])
                            {
                                $selected = 'selected';
                            }
                            else
                            {
                                $selected = '';
                            }
                            echo "<option value=\"$id\" $selected>$last_name, $first_name</option>";
                        }

            ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo msg('editpage_assign_department');?>
        </td>
        <td>
            <select name="file_department">
            <?php
                        $user_dept_id = $user_obj->getDeptId();
                        
                        // query to get a list of available departments
                        $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department ORDER BY name";
                        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
                        //////////////////Forbidden////////////////////
                        while(list($id, $name) = mysql_fetch_row($result))
                        {
                            if($id == $user_dept_id)
                            {
                                $selected = 'selected';
                            }
                            else
                            {
                                $selected = '';
                            }
                            echo "<option value=\"$id\" $selected>$name</option>";
                        }
             ?>
            </select>
        </td>
    </tr>
<?php } // End Admin ?>
    <tr>
        <td>
            <a class="body" href="help.html#Add_File_-_Category"  onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('category');?></a>
        </td>
        <td colspan=3><select tabindex=2 name="category" >
                    <?php
                    /////////////// Populate category drop down list//////////////
                    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}category ORDER BY name";
                    $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
    while(list($id, $name) = mysql_fetch_row($result))
    {
        echo '<option value="' . $id . '">' . $name . '</option>';
        }
        mysql_free_result ($result);
        ?>
            </select>
        </td>
    </tr>
    <?php
    udf_add_file_form();
    ?>
    <!-- Set Department rights on the file -->
    <TR id="departmentSelect">
        <TD>
            <a class="body" href="help.html#Add_File_-_Department" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_department');?></a>
        </TD>
        <TD COLSPAN=3>
            <hr /><SELECT tabindex=3 NAME="dept_drop_box" onChange ="loadDeptData(this.selectedIndex)">
                <option value=0> <?php echo msg('label_select_a_department');?></option>
                <option value=1> <?php echo msg('label_default_for_unset');?></option>
                <option value=2> <?php echo msg('label_all_departments');?></option>
                    <?php
                    //////Populate department drop down list/////////////////
                    $query = "SELECT id, name FROM {$GLOBALS['CONFIG']['db_prefix']}department ORDER BY name";
                    $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
                    //since we want value to corepodant to group id, 2 must be added to compesate for the first two none group related options.
                    while(list($id, $name) = mysql_fetch_row($result))
                    {
        $id+=2;
        //don't put quotes around values.  javascript might not work
        echo '	<option value ="' . $id . '" name="' . $name . '">'. $name . '</option>' . "\n";
    }
    mysql_free_result ($result);
    ?>
            </SELECT>
        </TD>
    </TR>
    <TR id="authorityRadio">
        <!-- Loading Authority radio_button group -->
        <TD><a class="body" href="help.html#Add_File_-_Authority" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_department_authority')?></a></td>
        <!-- <TD><a href="help.html" onClick="return popup(this, 'Help')">Authority</a></TD> -->
        <TD>
                <?php
                $query = "SELECT RightId, Description FROM {$GLOBALS['CONFIG']['db_prefix']}rights order by RightId";
    $result = mysql_query($query, $GLOBALS['connection']) or die("Error in querry: $query. " . mysql_error());
    while(list($RightId, $Description) = mysql_fetch_row($result))
    {
        echo $Description.'<input type ="radio" name ="'.$Description.'" value="' . $RightId . '" onClick="setData(this.name)"> |'."\n";
    }
    ?>
        <hr /></TD>
    </TR>
    <tr>
        <td>
            <a class="body" href="help.html#Add_File_-_Description" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_description')?></a>
        </td>
        <td colspan="3"><input tabindex="5" type="Text" name="description" size="50"></td>
    </tr>

    <tr>
        <td>
            <a class="body" href="help.html#Add_File_-_Comment" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_comment')?></a>
        </td>
        <td colspan="3"><textarea tabindex="6" name="comment" rows="4" onchange="this.value=enforceLength(this.value, 255);"></textarea></td>
    </tr>

    <TABLE id="specificUserPerms" border="0" cellspacing="0" cellpadding="3" NOWRAP>
        <tr nowrap>
            <td colspan="4" NOWRAP><b><?php echo msg('label_specific_permissions')?></b></td>
        </TR>
        <tr>
            <td>
                <a class="body" href="help.html#Rights_-_Forbidden" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_forbidden')?></a><br />
                <select class="multiView" tabindex="8" name="forbidden[]" multiple="multiple" size="10" onchange="changeForbiddenList(this, this.form);">
                        <?php

                        // query to get a list of available users
                        $query = "SELECT id, last_name, first_name FROM {$GLOBALS['CONFIG']['db_prefix']}user ORDER BY last_name";
                        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
                        //////////////////Forbidden////////////////////
                        while(list($id, $last_name, $first_name) = mysql_fetch_row($result))
                        {
                            $str = '<option value="' . $id . '"';
        // select current user's name
        $str .= '>'.$last_name.', '.$first_name.'</option>';
                            echo $str;
                        }
                        mysql_free_result ($result);
                        ?>
                </select>
            </td>
            <td>
                <a class="body" href="help.html#Rights_-_View" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_view')?></a><br />
                <select class="multiView" tabindex="9" name="view[]" multiple="multiple" size="10" onchange="changeList(this, this.form);">
                        <?php
                        ////////////////////View//////////////////////////
                        $query = "SELECT id, last_name, first_name FROM {$GLOBALS['CONFIG']['db_prefix']}user ORDER BY last_name";
                        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
                        //////////////////Forbidden////////////////////
                        while(list($id, $last_name, $first_name) = mysql_fetch_row($result))
                        {
                            $str = '<option value="' . $id . '"';
                            // select current user's name
        if($id == $_SESSION['uid'])
        {
                                $str .= ' selected';
                            }
                            $str .= '>'.$last_name.', '.$first_name.'</option>';
                            echo $str;
                        }
                        mysql_free_result ($result);
                        ?>
                </SELECT>
            </td>
            <td>
                <a class="body" href="help.html#Rights_-_Read" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_read')?></a><br />
            <select class="multiView" tabindex="10"  name="read[]" multiple="multiple" size="10"onchange="changeList(this, this.form);">
                        <?php
                        ////////////////////Read//////////////////////////
                        $query = "SELECT id, last_name, first_name FROM {$GLOBALS['CONFIG']['db_prefix']}user ORDER BY last_name";
                        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
                        //////////////////Forbidden////////////////////
                        while(list($id, $last_name, $first_name) = mysql_fetch_row($result))
                        {
        $str = '<option value="' . $id . '"';
        // select current user's name

                            if($id == $_SESSION['uid'])
                            {
                                $str .= ' selected';
                            }
                            $str .= '>'.$last_name.', '.$first_name.'</option>';
                            echo $str;
                        }
                        mysql_free_result ($result);
                        ?>
                </SELECT>
            </td>
            <td>
                <a class="body" href="help.html#Rights_-_Modify" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_modify')?></a><br />
            <select class="multiView" tabindex="11" name="modify[]" multiple="multiple" size="10"onchange="changeList(this, this.form);">
                        <?php
                        ////////////////////Read//////////////////////////
                        $query = "SELECT id, last_name, first_name FROM {$GLOBALS['CONFIG']['db_prefix']}user ORDER BY last_name";
    $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
    //////////////////Forbidden////////////////////
                        while(list($id, $last_name, $first_name) = mysql_fetch_row($result))
                        {
                            $str = '<option value="' . $id . '"';
                            // select current user's name
                            if($id == $_SESSION['uid'])
                            {
                                $str .= ' selected';
                            }
                            $str .= '>'.$last_name.', '.$first_name.'</option>';
                            echo $str;
                        }
                        mysql_free_result ($result);
                        ?>
                </SELECT>
            </td>
            <td>
                <a class="body" href="help.html#Rights_-_Admin" onClick="return popup(this, 'Help')" style="text-decoration:none"><?php echo msg('label_admin')?></a><br />
            <select class="multiView" tabindex="12" name="admin[]" multiple="multiple" size="10" onchange="changeList(this, this.form);">
    <?php
    ////////////////////Read//////////////////////////
    $query = "SELECT id, last_name, first_name FROM {$GLOBALS['CONFIG']['db_prefix']}user ORDER BY last_name";
    $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
    //////////////////Forbidden////////////////////
    while(list($id, $last_name, $first_name) = mysql_fetch_row($result))
    {
                $str = '<option value="' . $id . '"';
                // select current user's name
                if($id == $_SESSION['uid'])
                {
                    $str .= ' selected';
                }
                $str .= '>'.$last_name.', '.$first_name.'</option>';
                echo $str;
            }
            mysql_free_result ($result);
            ?>	</SELECT></td>

        </TR>
    </TABLE>
    <?php
        // Call the plugin API
        callPluginMethod('onBeforeAdd');
    ?>
    <table>
        <tr>
            <td colspan="3" align="center"><div class="buttons"><button class="positive" tabindex=7 type="Submit" name="submit" value="Add Document"><?php echo msg('submit')?></button></div></td>
        </tr>
    <?php
    $query = "SELECT name, id FROM {$GLOBALS['CONFIG']['db_prefix']}department ORDER BY name";
    $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
    while( list($dept_name, $dept_id) = mysql_fetch_row($result) )
    {
        if($dept_id == $current_user_dept)
        {
            echo "\n\t".'<input type="hidden" name="'. space_to_underscore($dept_name).'" value="1"> '."\n";
        }
        else
        {
            echo "\n\t".'<input type="hidden" name="'.space_to_underscore($dept_name).'" value="0"> '."\n";
        }
    }
    echo "\n\t".'<input type="hidden" name="default_Setting" value="0"> '."\n";
    mysql_free_result ($result);
    ?>
</form>
</table>
<?php
}
else 
{
    //invalid file
    if (empty($_FILES))
    {
        header('Location:error.php?ec=11');
        exit;
    }

    $numberOfFiles = count($_FILES['file']['name']);
    
    // First we need to make sure all files are allowed types
    for ($count = 0; $count < $numberOfFiles; $count++) {
        
        // Check ini max upload size
        if ($_FILES['file']['error'][$count] == 1) {
            $last_message = 'Upload Failed - check your upload_max_filesize directive in php.ini';
            header('Location: error.php?last_message=' . urlencode($last_message));
            exit;
        }

        // Lets lookup the try mime type
        $file_mime = File::mime($_FILES['file']['tmp_name'][$count], $_FILES['file']['name'][$count]);

        // check file type
        foreach ($GLOBALS['CONFIG']['allowedFileTypes'] as $thistype) {

            if ($file_mime == $thistype) {
                $allowedFile = 1;
                break;
            } else {
                $allowedFile = 0;
            }
        }
        
        // illegal file type!
        if ($allowedFile != 1)
        {
            $last_message = 'MIMETYPE: ' . $file_mime . ' Failed';
            header('Location:error.php?ec=13&last_message=' . urlencode($last_message));
            exit;
        }
    }
    
    //submited form
    for ($count = 0; $count<$numberOfFiles; $count++)
    {
        
        if ($GLOBALS['CONFIG']['authorization'] == 'True')
        {
            $lpublishable = '0';
        }
        else
        {
            $lpublishable= '1';
        }
        $result_array = array();
        
        // If the admin has chosen to assign the department
        // Set it here. Otherwise just use the session UID's department
        if($user_obj->isAdmin() && isset($_REQUEST['file_department']))
        {
            $current_user_dept = $_REQUEST['file_department'];
        }
        else
        {
            //get current user's department
            $query ="SELECT department FROM {$GLOBALS['CONFIG']['db_prefix']}user where id=$_SESSION[uid]";
            $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
            if(mysql_num_rows($result) != 1)
            {
                header('Location:error.php?ec=14');
                exit; //non-unique error
            }
            list($current_user_dept) = mysql_fetch_row($result);
        }
        // File is bigger than what php.ini post/upload/memory limits allow.
        if($_FILES['file']['error'][$count] == '1')
        {
           header('Location:error.php?ec=26');
            exit;
        }

        // File too big?
        if($_FILES['file']['size'][$count] >  $GLOBALS['CONFIG']['max_filesize'] )
        {
            header('Location:error.php?ec=25');
            exit;
        }

        // Check to make sure the dir is available and writeable
        if (!is_dir($GLOBALS['CONFIG']['dataDir']))
        {
            $last_message=$GLOBALS['CONFIG']['dataDir'] . ' missing!';
            header('Location:error.php?ec=23&last_message=' .$last_message);
            exit;
        }
        else
        {
            if (!is_writeable($GLOBALS['CONFIG']['dataDir']))
            {
                $last_message=msg('message_folder_perms_error'). ': ' . $GLOBALS['CONFIG']['dataDir'] . ' ' . msg('message_not_writeable');
                header('Location:error.php?ec=23&last_message=' .$last_message);
                exit;
            }
        }
        // all checks completed, proceed!

        // Run the onDuringAdd() plugin function
        callPluginMethod('onDuringAdd');

        // If the admin has chosen to assign the owner
        // Set it here. Otherwise just use the session UID
        if($user_obj->isAdmin() && isset($_REQUEST['file_owner']))
        {
            $owner_id = $_REQUEST['file_owner'];
        }
        else
        {
            $owner_id = $_SESSION['uid'];
        }
        
        // INSERT file info into data table
        $query = "INSERT INTO 
        {$GLOBALS['CONFIG']['db_prefix']}data (
            status,
            category,
            owner,
            realname,
            created,
            description,
            department,
            comment,
            default_rights,
            publishable
        )
            VALUES
        (
            0,
            '" . addslashes($_REQUEST['category']) . "',
            '" . addslashes($owner_id) . "',
            '" . addslashes($_FILES['file']['name'][$count]) . "',
            NOW(),
            '" . addslashes($_REQUEST['description']) . "',
            '" . addslashes($current_user_dept) . "',
            '" . addslashes($_REQUEST['comment']) . "',
            '" . addslashes($_REQUEST['default_Setting']) . "',
            $lpublishable
        )";

        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
        // get id from INSERT operation
        $fileId = mysql_insert_id($GLOBALS['connection']);

        udf_add_file_insert($fileId);

        //Find out the owners' username to add to log
        $query = "SELECT username FROM {$GLOBALS['CONFIG']['db_prefix']}user where id='$_SESSION[uid]'";
        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());
        list($username) = mysql_fetch_row($result);

        // Add a log entry
        $query = "INSERT INTO {$GLOBALS['CONFIG']['db_prefix']}log (id,modified_on, modified_by, note, revision) VALUES ( '$fileId', NOW(), '" . addslashes($username) . "', 'Initial import', 'current')";
        $result = mysql_query($query, $GLOBALS['connection']) or die ("Error in query: $query. " . mysql_error());


        //Insert Department Rights into dept_perms
        $query = "SELECT name, id FROM {$GLOBALS['CONFIG']['db_prefix']}department ORDER BY name";
        $result = mysql_query($query, $GLOBALS['connection']) or die("Error in query: $query. " . mysql_error() );
        while( list($dept_name, $id) = mysql_fetch_row($result) )
        {
            //echo "Dept is $dept_name";
            $query = "INSERT INTO {$GLOBALS['CONFIG']['db_prefix']}dept_perms (fid, rights, dept_id) VALUES('$fileId', '" . addslashes($_REQUEST[space_to_underscore($dept_name)]) . "', '$id')";
            mysql_query($query, $GLOBALS['connection']) or die("Error in query: $query. " . mysql_error() );
        }
        // Search for simular names in the two array (merge the array.  repetitions are deleted)
        // In case of repetitions, higher priority ones stay.
        // Priority is in this order (admin, modify, read, view)
        $filedata = new FileData($fileId, $GLOBALS['connection'], DB_NAME);

        if  (isset ($_REQUEST['admin']))
        {
            $result_array = advanceCombineArrays($_REQUEST['admin'], $filedata->ADMIN_RIGHT, $_REQUEST['modify'], $filedata->WRITE_RIGHT);
        }

        if (isset ($_REQUEST['read']))
        {
            $result_array = advanceCombineArrays($result_array, 'NULL', $_REQUEST['read'], $filedata->READ_RIGHT);
        }

        if (isset ($_REQUEST['view']))
        {
            $result_array = advanceCombineArrays($result_array, 'NULL', $_REQUEST['view'], $filedata->VIEW_RIGHT);
        }

        if (isset ($_REQUEST['forbidden']))
        {
            $result_array = advanceCombineArrays($result_array, 'NULL', $_REQUEST['forbidden'], $filedata->FORBIDDEN_RIGHT);
        }
        // INSERT user permissions - view
        for($i = 0; $i<sizeof($result_array); $i++)
        {
            $query = "INSERT INTO {$GLOBALS['CONFIG']['db_prefix']}user_perms (fid, uid, rights) VALUES('$fileId', '".$result_array[$i][0]."','". $result_array[$i][1]."')";
            $result = mysql_query($query, $GLOBALS['connection']) or die("Error in query: $query" .mysql_error());
        }

        // use id to generate a file name
        // save uploaded file with new name
        $newFileName = $fileId . '.dat';

        if (!is_uploaded_file($_FILES['file']['tmp_name'][$count]))
        {
            header('Location: error.php?ec=18');
            exit;
        }
        move_uploaded_file($_FILES['file']['tmp_name'][$count], $GLOBALS['CONFIG']['dataDir'] . '/' . $newFileName);

        //copy($GLOBALS['CONFIG']['dataDir'] . '/' . ($fileId-1) . '.dat', $GLOBALS['CONFIG']['dataDir'] . '/' . $newFileName);
        
        // back to main page
        $message = urlencode(msg('message_document_added'));

        // Call the plugin API
        callPluginMethod('onAfterAdd', $fileId);
    }
        
    AccessLog::addLogEntry($fileId, 'A');

    header('Location: details.php?id=' . $fileId . '&last_message=' . $message);
    exit;
}
?>
<script type="text/javascript">

    var index = 0;
    var index2 = 0;
    var begin_Authority;
    var end_Authority;
    var frm_main = document.main;
    var dept_drop_box = frm_main.dept_drop_box;
    //Find init position of Authority
    while(frm_main.elements[index].name != "forbidden")
    {       index++;        }
    index2 = index;         //continue the search from index to avoid unnessary iteration
    // Now index contains the position of the view radio button
    //Next search for the position of the admin radio button
    while(frm_main.elements[index2].name != "admin")
    {       index2++;       }
    //Now index2 contains the position of the admin radio button
    //Set the size of the array
    begin_Authority = index;
    end_Authority = index2;
    /////////////////////////////Defining event-handling functions///////////////////////////////////////////////////////
    var num_of_authorities = 4;
    function showData()
    {
        alert(frm_main.elements["Information_Systems"].value);
        alert(frm_main.elements["Test"].value);
        alert(frm_main.elements["Toxicology"].value);
    }
    function test()
    {
        alert(frm_main.elements["default_Setting"].value);
    }

    //loadData(_selectedIndex) load department data array
    //loadData(_selectedIndes) will only load data at index=_selectedIndex-1 of the array since
    //since _selectedIndex=0 is the "Please choose a department" option
    //when _selectedIndex=0, all radio button will be cleared.  No department[] will be set
    function loadDeptData(_selectedIndex)
    {
        if(_selectedIndex > 0)  //does not load data for option 0
        {
            switch(departments[(_selectedIndex-1)].getRights())
            {
                case -1:
                    frm_main.forbidden.checked = true;
                    deselectOthers("forbidden");
                    break;
                case 0:
                    frm_main.none.checked = true;
                    deselectOthers("none");
                    break;
                case 1:
                    frm_main.view.checked = true;
                    deselectOthers("view");
                    break;
                case 2:
                    frm_main.read.checked = true;
                    deselectOthers("read");
                    break;
                case 3:
                    frm_main.write.checked = true;
                    deselectOthers("write");
                    break;
                case 4:
                    frm_main.admin.checked = true;
                    deselectOthers("admin");
                    break;
                default: break;
            }
        }
        else
        {
            index = begin_Authority;
            while(index <= end_Authority)
            {
                frm_main.elements[index++].checked = false;
            }
        }
    }

    //Deselect other button except the button with the name stored in selected_rb_name
    //Design to control the rights radio buttons
    function deselectOthers(selected_rb_name)
    {
        var index = begin_Authority;
        while(index <= end_Authority)
        {
            if(frm_main.elements[index].name != selected_rb_name)
            {
                frm_main.elements[index].checked = false;
            }
            index++;
        }
    }

    function spTo_(string)
    {
        // Joe Jeskiewicz fix
        var pattern = / /g;
        return string.replace(pattern, "_");
        //	return string.replace(" ", "_");
    }

    function setData(selected_rb_name)
    {
        var index = 0;
        var current_selected_dept =  dept_drop_box.selectedIndex - 1;
        var current_dept = departments[current_selected_dept];
        deselectOthers(selected_rb_name);
        //set right into departments
        departments[current_selected_dept].setRights(frm_main.elements[selected_rb_name].value);
        //Since the All and Defualt department are abstractive departments, hidden fields do not exists for them.
        if(current_selected_dept-2 >= 0) // -1 from above and -2 now will set the first real field being 0
        {
            //set department data into hidden field
            frm_main.elements[spTo_( current_dept.getName() )].value = current_dept.getRights();
        }
        departments[current_selected_dept].setFlag("true");
        if(  current_selected_dept == default_Setting_pos )  //for default user option
        {
            frm_main.elements['default_Setting'].value = frm_main.elements[selected_rb_name].value;
            while (index< dept_drop_box.length)
            {
                //do not need to set "All Department" and "Default Department"  they are only abstracts
                if(departments[index].issetFlag() == false && index != all_Setting_pos && index != default_Setting_pos)
                {
                    //set right radio buton's value into all Department that is available on the database
                    departments[index].setRights(frm_main.elements[selected_rb_name].value);
                    //set right onto hidden valid hidden fields to communicate with php
                    frm_main.elements[spTo_(departments[index].getName())].value = frm_main.elements[selected_rb_name].value;
                }
                index++;
            }
            index = 0;
        }
        if( current_selected_dept == all_Setting_pos) //for all user option. linked with predefine value above.
        {
            index = 0;
            while(index < (dept_drop_box.length - 1))
            {
                if(index != default_Setting_pos && index != all_Setting_pos) //Don't set default and All
                {
                    //All setting acts like the user actually setting the right for all the department. -->setFlag=true
                    departments[index].setFlag(true);
                    //Set rights into department array
                    departments[index].setRights(frm_main.elements[selected_rb_name].value );
                    //Set rights into hidden fields for php
                    frm_main.elements[spTo_(departments[index].getName())].value = frm_main.elements[selected_rb_name].value;
                }
                index++;
            }
        }
    }
    function changeList(select_list, current_form)
    {
        var select_list_array = new Array();
        select_list_array[0] = current_form['view[]'];
        select_list_array[1] = current_form['read[]'];
        select_list_array[2] = current_form['modify[]'];
        select_list_array[3] = current_form['admin[]'];
        for( var i=0; i < select_list_array.length; i++)
        {
            if(select_list_array[i] == select_list)
            {
                for(var j=0; j< select_list.options.length; j++)
                {
                    if(select_list.options[j].selected)
                    {
                        for(var k=0; k < i; k++)
                        {
                            select_list_array[k].options[j].selected=true;
                        }//end for
                        current_form['forbidden[]'].options[j].selected=false;
                    }//end if
                    else
                    {
                        for(var k=i+1; k < select_list_array.length; k++)
                        {
                            select_list_array[k].options[j].selected=false;
                        }
                    }//end else
                }//end for
            }//end if
        }//end for
    }
    function changeForbiddenList(select_list, current_form)
    {
        var select_list_array = new Array();
        select_list_array[0] = current_form['view[]'];
        select_list_array[1] = current_form['read[]'];
        select_list_array[2] = current_form['modify[]'];
        select_list_array[3] = current_form['admin[]'];
        for(var i=0; i < select_list.options.length; i++)
        {
            if(select_list.options[i].selected==true)
            {
                for( var j=0; j < select_list_array.length; j++)
                {
                    select_list_array[j].options[i].selected=false;
                }//end for
            }
        } //end for
    }

</script>
    <?php
    draw_footer();
