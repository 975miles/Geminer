function loadGems() {
    return new Promise((res, rej) => 
        $.getJSON("/a/data/gems.json", data => res(data))
    );
}

function showInfo(error="Unknown error occurred.", title="Error!") {
    $('#infoBoxTitle').html(title);
    $('#infoBoxContents').html(error);
    $('#infoBox').modal(); //show modal on page
}