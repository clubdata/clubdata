var HelpWindow = "";

/**
 * Use ajax calls where available. Set to true to enable ajax calls via jquery
 * false if you do prefer submitting pages
 *
 * @var boolean use ajax calls where available
 */
var useAjax = true;

function checkIfRange(selObj)
{
  objectName = /^(.*)_select/.exec(selObj.name)[1];
  selectedValue = selObj.options[selObj.selectedIndex].value;

//   alert(objectName + ": " + selectedValue);

  if ( selectedValue.substr(0,3) == 'BET' )
  {
    document.getElementById(objectName + '_rangeEnd').style.display = 'inline';
  }
  else
  {
    document.getElementById(objectName + '_rangeEnd').style.display='none';
  }


}

function doSubmit(mod, view)
{
        document.forms[0].view.value = view
        document.forms[0].mod.value = mod
        return true;
}

function doAction(mod, view, myAction)
{
// 	alert("Action: " + myAction + "(Elements: " + document.forms[0].elements.length);
    document.forms[0].view.value = view;
    document.forms[0].mod.value = mod;
	for ( i=0 ; i < document.forms[0].elements.length; i++ )
	{
// 		alert("Element: " + document.forms[0].elements[i].name );
		if ( document.forms[0].elements[i].name == 'Action' )
		{
// 			alert("Found Action: " + i);
			document.forms[0].elements[i].value = myAction;
		}
	}
    document.forms[0].submit();
    return true;
}

function changeColorOnKey(obj)
{
    obj.style.backgroundColor = "red";
}

function changeColorIfChanged(obj, defVal)
{
    if ( obj.value != defVal )
    {
        obj.style.backgroundColor = "red";
    }
    else
    {
        obj.style.backgroundColor = "white";
    }

}

function delRow(ic, txt, link, linkParam)
{
    if ( confirm(txt) )
    {
        ic.href = link + linkParam;
        return true;
    }
    return false;
  }

function resetFormAndColor()
{
    document.forms["MitgliederForm"].reset();
    for (i = 0; i < document.forms["MitgliederForm"].elements.length; i++ )
    {
        document.forms["MitgliederForm"].elements[i].style.backgroundColor = "white";
    }
}

function openHelp(HelpTxt)
{
    if (HelpWindow != "")
    {
        if(HelpWindow.closed != true)
        {
            HelpWindow.location.href = HelpTxt;
            HelpWindow.focus();
            return;
        }
    }

    HelpWindow = open(HelpTxt,
                "Help",
                "height=200,width=200,scrollbars=yes");
}

function checkEnter(event)
{
  var code = 0;
  NS4 = (document.layers) ? true : false;

  if (NS4)
    code = event.which;
  else
    code = event.keyCode;

//  if (code==13)
//    submitMemberMainForm();
}

function isEnter(event)
{
  var code = 0;
  NS4 = (document.layers) ? true : false;

  if (NS4)
    code = event.which;
  else
    code = event.keyCode;

  if (code==13)
    return true;
  else
    return false;
}

function bottomMemberOrSearch(event, myFrame)
{
  if ( isEnter(event) )
    {
      var txt = myFrame.value;

      if ( txt.match(/^[0-9]+$/) )
      {
        location = ('?mod=members&MemberID=' + txt);
      }
      else
      {
        location = ('?mod=list&view=Memberlist&quicksearch=' + txt);
      }
      // Don't do submit of bottom FORM
      return false;
    }
  return true;
}
function deleteMember(mbr,text)
{
    if ( confirm(unescape(text) ) )
    {
        location = ('?mod=members&MemberID=' + mbr + '&Action=DELETE');
        return false;
    }
    return false;
}

function newMember(text)
{
    if ( confirm(unescape(text)) )
    {
        location = ('?mod=members&Action=INSERT');
        return false;
    }
    return false;
}

function SetSelected(val, selName)
{
    //alert("SETSELECTED");
    dml=document.forms[0];
    len = dml.elements.length;
    var i=0;
    for( i=0 ; i<len ; i++)
    {
//         alert("SELECT: " + dml.elements[i].name + ", Compare: " + selName);
        if (dml.elements[i].type == "select-multiple" && (selName == "" || dml.elements[i].name==selName))
        {
            //alert("SELECT: " + dml.elements[i].name);
            for ( j=0 ; j < dml.elements[i].options.length; j++ )
            {
                if ( dml.elements[i].options[j].value != "" )
                {
                    dml.elements[i].options[j].selected=val;
                }
                else
                {
                    dml.elements[i].options[j].selected= !val;
                }
            }
            return;
        }
    }
}

function SetChecked(val,chkName)
{
    dml=document.forms[0];
    len = dml.elements.length;
    var i=0;
    for( i=0 ; i<len ; i++)
    {
        if (dml.elements[i].type == "checkbox" && (chkName == "" || dml.elements[i].name==chkName))
        {
            dml.elements[i].checked=val;
        }
    }
}

function toggleChecked(id, checkState, optionStr)
{
	if ( useAjax )
	{
		$.post("index.php?" + optionStr, { byAjax: true, Action: "SETCHECKED", id: id, newState: checkState } );
	}
	else
	{
		$linkTxt = ("?Action=SETCHECKED&id=" + id + "&newState=" + checkState + optionStr);
		location = ($linkTxt);
	}
}


/* Display modal form to query invoice datas */
function askInvoiceData()
{
	show_modal('invoiceData');

	return false;
}


/**
 * Do setup stuff after page is loaded.
 *
 * This piece of code should for convinience be the last one in js_main.js
 *
 */
$(document).ready(function() {

	// Start Tooltips, @see http://plugins.learningjquery.com/cluetip/
	if ( typeof show_tooltip == 'undefined' || show_tooltip != false )
	{
		$('a.help').cluetip({width: '200px', cluetipClass: 'rounded', dropShadow: false,  sticky: false, closePosition: 'bottom', /* */ arrows: true});
	}

});
