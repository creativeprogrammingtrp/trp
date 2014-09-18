// JavaScript Document
function pagination( callbackFunction, pagID, records, page, numberOfDisplayingLinks, recordsPerPage )
{
	if(!pagID || pagID == '')
		return false;
	if(!records || records <= 0 || records <= recordsPerPage || !document.getElementById(pagID) )
	{
		$("#"+pagID).empty();
		return false;	
	}	
	if(!page || page <= 0)
		page = 1;
	if( !recordsPerPage || recordsPerPage <= 0 )
		recordsPerPage = 10;
		
	//calculate number of page 
	var numberOfPages = Math.ceil(records/recordsPerPage);
	if(numberOfPages <= 1)
		return false;
		
	if(!numberOfDisplayingLinks || numberOfDisplayingLinks <=0 || numberOfDisplayingLinks >= 20)
		numberOfDisplayingLinks = 5;
		
	var prevDisabled = '';
	var nextDisabled = '';
	if(page == 1)//first page
		prevDisabled = 'class= "disabled"';	
	else if(page == numberOfPages)//last page
		nextDisabled = 'class= "disabled"';
	
	var firstLink = Math.floor(page - numberOfDisplayingLinks/2);
	if(firstLink <= 0) 
		firstLink = 1;
	var lastLink = firstLink + numberOfDisplayingLinks - 1;
	if(lastLink > numberOfPages)
	{
		lastLink = numberOfPages;
		if( lastLink - numberOfDisplayingLinks > 0 && lastLink - numberOfDisplayingLinks < firstLink )
			firstLink = lastLink - numberOfDisplayingLinks + 1 ;
	}
	
	var pagStr = '';
	pagStr += '<ul>';
	pagStr += '<li '+prevDisabled+'><a title="First" class="invarseColor" href="javascript: '+callbackFunction+'(1);">First</a></li>';
	pagStr += '<li '+prevDisabled+'><a title="Previous" class="invarseColor" href="javascript: '+callbackFunction+'('+(parseInt(page)-1)+');">Prev</a></li>';
	//pagination
	for(var i = firstLink; i <= lastLink; i++)
	{
		var active = (i == page)? ' class="active"' :'';
		pagStr += '<li '+active+'><a class="invarseColor" href="javascript: '+callbackFunction+'('+i+');">'+i+'</a></li>';
	}
	pagStr += '<li '+nextDisabled+'><a class="invarseColor" title="Next" href="javascript: '+callbackFunction+'('+(parseInt(page)+1)+');">Next</a></li>';
	pagStr += '<li '+nextDisabled+'><a title="First" class="invarseColor" href="javascript: '+callbackFunction+'('+numberOfPages+');">Last</a></li>';
	pagStr += '</ul>';
	$("#"+pagID).html(pagStr);
}// function shop_pagination

function showListItemInfo()
{
	var startRecord = (page-1)*recordsPerPage + 1;
	var endRecord = startRecord+recordsPerPage-1;
	if(endRecord > numberOfRecords)
		endRecord = numberOfRecords;
	$(".list-info").empty();
	if(numberOfRecords > 0)
		$(".list-info").append("Showing "+startRecord+" to "+endRecord+" of "+numberOfRecords+" entries");	
}//function showItemInfo


function getParameter(paramName) {
	var str = window.location+"";
	var arr_hash = str.split('#');
	if (arr_hash.length<=1) return null; 
	var searchString = arr_hash[1];
	i=0, val='', params = searchString.split("&");
  	for (i=0;i<params.length;i++) {
  		val = params[i].split("=");
    	if (val[0] == paramName) {
      		return unescape(val[1]);
    	}
  	}
  	return null;
}