// <!-- auth.js -->
// Use api key instead of oauth
var apiKey = 'AIzaSyB4__3jfqqrcViXDrpcmSfr5JkX_ObL6bY';

// Upon loading, the Google APIs JS client automatically invokes this callback.
googleApiClientReady = function () {
    gapi.client.setApiKey(apiKey);
    loadAPIClientInterfaces();
};

// Load the client interfaces for the YouTube Analytics and Data APIs, which
// are required to use the Google APIs JS client. More info is available at
// http://code.google.com/p/google-api-javascript-client/wiki/GettingStarted#Loading_the_Client
function loadAPIClientInterfaces() {
    gapi.client.load('youtube', 'v3', function () {
        handleAPILoaded();
    });
}

// After the API loads, call a function to get the uploads playlist ID.
function handleAPILoaded() {
    requestUserUploadsPlaylistId();
}

/***************************
        Now my code
***************************/
// Function to make an array unique
var arrayUnique = function(a) {
    return a.reduce(function(p, c) {
        if (p.indexOf(c) < 0) p.push(c);
        return p;
    }, []);
};

// Define some variables 
var $contianer = $('.html-container');
var $status = $('.status');
var playlistId, nextPageToken, prevPageToken, allPlaylistItems = [], allUcdtrampNames = [];
var competitions = ['in house', 'in-house', 'intervarsities', 'ssto', 'isto', 'colours', 'dubl?in open', 'scotland', 'inters', 'national finals', 'nationals', 'Athy Regionals', 'UCD Regionals', 'athy', 'regionals', 'qualifiers', 'UCLan'];
var levelsEtc = ['set', 'vol', 'routine', 'Two-Trick', 'dmt', 'Tumbling', 'pro', 'mens?', 'ladies', 'Novice', 'Intermediate', 'Intervanced', 'Advanced', 'Elite', 'gold', 'silver', 'bronze', '[,-:.()]'];
var handPickedRandomShit = ['Chair Sex','Group Hug','Clare d man Beater','Gnomes','March','D Speach','Hairdressing','Hallway Antics','Pantless Ryan','Fuck d Dealer','Rosie and Fiona Swing Dancing','Sisters','Suck on a Lemon','Trampolining at','Street Singin'];

// Call the Data API to retrieve the playlist ID that uniquely identifies the
// list of videos uploaded to the currently authenticated user's channel.
function requestUserUploadsPlaylistId() {
    // See https://developers.google.com/youtube/v3/docs/channels/list
    var request = gapi.client.youtube.channels.list({
        forUsername: 'ucdtramp',
        part: 'contentDetails'
    });
    request.execute(function (response) {
        playlistId = response.result.items[0].contentDetails.relatedPlaylists.uploads;
        requestVideoPlaylist(playlistId);
    });
}

// Retrieve the list of videos in the specified playlist.
function requestVideoPlaylist(playlistId, pageToken) {
    var requestOptions = {
        playlistId: playlistId,
        part: 'snippet',
        fields: 'items/snippet(publishedAt,thumbnails/high,title,resourceId/videoId),nextPageToken',
        maxResults: 50
    };
    if (pageToken) {
        requestOptions.pageToken = pageToken;
    }
    var request = gapi.client.youtube.playlistItems.list(requestOptions);
    request.execute(function (response) {
        // Get all items from the result
        var playlistItems = response.result.items;
        $.each(playlistItems, function (index, item) {
            videoSnippet = item.snippet;

            // Isolate the video's year
            var yearRegexp = /((?:\d{2}){1,2})/igm;
            var yearMatch = yearRegexp.exec(videoSnippet.title);
            videoSnippet.ucdtrampYear = 'no year';
            if(yearMatch){
                videoSnippet.ucdtrampYear = (yearMatch[1].length == 2)?
                    '20'+yearMatch[1] : yearMatch[1];
            }

            // Isolate the video competition
            name = videoSnippet.title.replace(/[0-9]/igm, '');
            videoSnippet.ucdtrampComp = 'no comp';
            for (var i in competitions) {
                var compRegex = new RegExp('('+competitions[i]+')', 'igm');
                var compMatch = compRegex.exec(name);
                if(compMatch){
                    // console.log(compMatch[1]);
                    videoSnippet.ucdtrampComp = compMatch[1]; // save comp in json
                    name = name.replace(compMatch[1], ''); // use to isolate name
                    break;
                }
            }

            // Try to isolate a person's name from the title
            for (var j in levelsEtc) {
                regex = new RegExp(levelsEtc[j], "igm");
                name = name.replace(regex, '');
            }
            videoSnippet.ucdtrampName = name.trim();

            // Collect synchro vids
            if (name.search(/synchro/igm) > -1){
                videoSnippet.ucdtrampName = 'Synchro';
            }

            // Isolate random vids
            for (j in handPickedRandomShit) {
                regex = new RegExp(handPickedRandomShit[j], "i");
                if (videoSnippet.ucdtrampName.search(regex) > -1){
                    videoSnippet.ucdtrampName = 'Msc';
                    videoSnippet.ucdtrampYear = 'Year is not important';                    
                }
            }
            if (videoSnippet.ucdtrampYear == 'no year' || videoSnippet.ucdtrampComp == 'no comp' || videoSnippet.ucdtrampName === ''){
                videoSnippet.ucdtrampName = 'Msc';
                videoSnippet.ucdtrampYear = 'Year is not important';
            }

            // Add each item to an array
            allPlaylistItems.push(videoSnippet);
            allUcdtrampNames.push(videoSnippet.ucdtrampName);
        });

        // Request next page of items
        nextPageToken = response.result.nextPageToken;
        if (nextPageToken) {
            // Update how many have been found
            $status.html(allPlaylistItems.length + ' videos fetched so far...');
            // Get next page
            requestVideoPlaylist(playlistId, nextPageToken);
        } else {
            $status.html(allPlaylistItems.length + ' videos fetched in total');
            $status.append('<br><strong>Finished</strong><br>Saving...');

            // Spit out something useful
            handleVideoPlaylist();
        }
    });
}

handleVideoPlaylist = function(){
    // Sort isolated names
    allUcdtrampNames = arrayUnique(allUcdtrampNames).sort();

    var json = {};
    var html = '';

    for(var nameIndex in allUcdtrampNames){
        var thisName = allUcdtrampNames[nameIndex];
        if (typeof thisName == 'function')
            continue;

        json[thisName] = [];

        // Go through each video to find one's with thisName
        for(var videoIndex in allPlaylistItems){
            var thisVideo = allPlaylistItems[videoIndex];

            if (thisVideo.ucdtrampName == thisName){
                json[thisName].push({
                    'year': thisVideo.ucdtrampYear,
                    'comp': thisVideo.ucdtrampComp,
                    'title': thisVideo.title,
                    'urlId': thisVideo.resourceId.videoId,
                    'thumb': thisVideo.thumbnails.high.url
                });
            }
        }
    }
    console.log(json);
    saveJson(json);

    for(nameIndex in allUcdtrampNames){
        var thisName = allUcdtrampNames[nameIndex];
        html += '<h3>'+thisName+'</h3>';

        // Go through each video to find one's with thisName
        for(videoIndex in allPlaylistItems){
            var thisVideo = allPlaylistItems[videoIndex];

            if (thisVideo.ucdtrampName == thisName){
                html += 'Year: '+thisVideo.ucdtrampYear+' - Competition: '+thisVideo.ucdtrampComp+' - '+
                    'Video: <a target="_blank" href="https://www.youtube.com/edit?o=U&video_id='+thisVideo.resourceId.videoId+'">'+thisVideo.title+'</a> <br>';
            }
        }
    }

    // Add html to the page
    $contianer.append(html);
};

function saveJson(json){
    json = JSON.stringify(json);
    $.ajax({
        type: 'POST',
        url : 'youtubevids.php',
        data: 'action=saveJson&json='+ json,
        dataType: 'text', // server return type
        success: function(response){
            if (response === '')
                $status.append('<strong style="color:green">New JSON has been saved!</strong>');
            else
                $status.append('<strong style="color:red">Something went wrong! Tell the webmaster</strong><br>'+response);
        }
    });
}