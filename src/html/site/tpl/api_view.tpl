<html lang="en">
    <style>
        textarea{
            width:100%;
        }
    </style>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <table class="table" border="1" style="width:100%;">
        <thead>
            <tr>
                <th style="width:30%;">Request</th>
                <th>Response</th>
                <th>send</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1. login:<p>
                    <textarea name="" id="login_json" cols="30" rows="10">
{
    "login_name":"kdan"
}
                    </textarea>
                </td>
                <td>
                    <pre id='login_response'>
                    </pre>
                </td>
                <td>
                    <input type="button" value="login" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>2. List all pharmacies that are open at a certain datetime<p>
                    <textarea name="" id="openingByDateTime_json" cols="30" rows="10">
{
    "token": "1YqCxzvO0sBRMGjGQjx81KTKa54z40tqBy0_-UhIvss",
    "data":{
            "dateTime" : "2021-02-07 12:12:20"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='openingByDateTime_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="openingByDateTime" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>3.List all pharmacies that are open on a day of the week, at a certain time<p>
                    <textarea name="" id="openingByWeekday_json" cols="30" rows="10">
{
    "token": "{ TOKEN  }",
    "data":{
            "weekDay" : "Mon"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='openingByWeekday_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="openingByWeekday" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>4.List all masks that are sold by a given pharmacy, sorted by mask name or mask price<p>
                    <textarea name="" id="maskItemByPharmacies_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "pharmaciesUUID" : "1adc6ab2-afbe-4428-a7e5-ca28c593969b" ,
            "sort"  : "name"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='maskItemByPharmacies_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="maskItemByPharmacies" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>5.List all pharmacies that have more or less than x mask products within a price range<p>
                    <textarea name="" id="maskPharmaciesByPriceRange_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "minPrice" : "20" ,
            "maxPrice"  : "30"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='maskPharmaciesByPriceRange_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="maskPharmaciesByPriceRange" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>6.Search for pharmacies or masks by name, ranked by relevance to search term<p>
                    <textarea name="" id="searchMaskPharmacies_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "condition" : "mask" ,
            "keyword"  : "Ani"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='searchMaskPharmacies_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="searchMaskPharmacies" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>7.The top x users by total transaction amount of masks within a date range<p>
                    <textarea name="" id="sellTotalTopByDate_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "count" : "5" ,
            "startDate"  : "2021-01-01",
            "endDate"  : "2021-11-30"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='sellTotalTopByDate_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="sellTotalTopByDate" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>8.The total amount of masks and dollar value of transactions that happened within a date range<p>
                    <textarea name="" id="sellTotalMaskTotalByDate_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "count" : "5" ,
            "startDate"  : "2021-01-01",
            "endDate"  : "2021-11-30"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='sellTotalMaskTotalByDate_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="sellTotalMaskTotalByDate" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>9.Edit pharmacy name, mask name, and mask price<p>
                    <textarea name="" id="editPharmaciesAndMask_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "editType" : "mask" ,
            "uuid" : "a3b4a5d9-2a26-4dd0-8378-8cdc97f42005",
            "editData"  : {
                "name" : "Pill Pack12"
            }
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='editPharmaciesAndMask_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="editPharmaciesAndMask" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>10.Remove a mask product from a pharmacy given by mask name<p>
                    <textarea name="" id="deleteMask_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "pharmaciesUUID" : "22e4bf47-c2c9-4cd9-b11f-93f8ebac89b6" ,
            "maskName" : "AniMask (blue) (10 per pack)"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='deleteMask_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="deleteMask" class="send_bt"><br>
                </td>
            </tr>
            <tr>
                <td>11.Process a user purchases a mask from a pharmacy, and handle all relevant data changes in an atomic transaction<p>
                    <textarea name="" id="userBuyMask_json" cols="30" rows="10">
{
    "token": "{ TOKEN }",
    "data":{
            "userUUID" : "11dedce9-1551-455e-9bc4-0e15f78b90ee" ,
            "maskUUID"  : "feda033f-fa63-445d-826b-5d191604c82c"
    }
}
                    </textarea>
                </td>
                <td>
                    <pre id='userBuyMask_response'>

                    </pre>
                </td>
                <td>
                    <input type="button" value="userBuyMask" class="send_bt"><br>
                </td>
            </tr>
        </tbody>
    </table>

</html>
<script>
$(".send_bt").click(function (e) {
    var action = $(this).val();
    var data = $('#'+action+'_json').val().trim()
    data = data.trim();
    console.log(data);
    $("#"+action+"_response").text('');

    $.ajax({
    url: '{domain}/phantom_mask/src/html/api/'+action+'/index.php',
    type: 'POST',
    dataType: 'json',
    async: false,
    data: data,
    })
    .done(function(result) {
        var j= JSON.stringify(result,undefined,4)
        $("#"+action+"_response").text(j);
        if(result.error){
            //alert(result.msg);
        }else{
            var data = result.data
            var token = data.token
            $.cookie('token', token);
        }
    })
    .fail(function() {
        alert('發生錯誤');
    });
});


$("#login").click(function (e) {
    $.ajax({
    url: '{host}/phantom_mask/src/html/api/login/index.php',
    type: 'POST',
    dataType: 'json',
    async: false,
    data: JSON.stringify({"login_name":"kdan"}),
    })
    .done(function(result) {
        var j= JSON.stringify(result,undefined,4)
        $("#login_response").html(j);
        if(result.error){
            alert(result.msg);
        }else{
            var data = result.data
            var token = data.token
            $.cookie('token', token);
        }
    })
    .fail(function() {
        alert('ERROR');
    });
});



</script>