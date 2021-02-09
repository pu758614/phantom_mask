# Response
## API Document (required)
######Import the POSTMAN json file [here](https://github.com/pu758614/phantom_mask/blob/master/phantom_mask.postman_collection.json)
### 1. LOGIN
##### POST URL
  `http://bibleline2.herokuapp.com/phantom_mask/src/html/api/login/index.php`
##### Request json
```json
{"login_name":"kdan"}
```
##### Description
   * login_name : input「kdan」


##### Response json
```json
{
    "error": false,
    "msg": "",
    "data": {
        "token": "1YqCxzvO0sCujpO6bsiRqTv2ZvEsTuI6By0_-UhIvss"
    }
}
```



  ### 2. List all pharmacies that are open at a certain datetime
##### POST URL
  `http://bibleline2.herokuapp.com/phantom_mask/src/html/api/openingByDateTime/index.php`

##### Request
   ```json
{
		"token": "{{TOKEN}}",
		"data":{
			"dateTime" : "2021-02-07 12:12:20"
		}
}
```

##### Description
   * token : Token obtained by login
   * data
     * dateTime : search date time


##### Response
   ```json
{
    "error": false,
    "msg": "",
    "data": [
        {
            "pharmaciesUUID": "90eb0d71-9fb7-4d92-a365-3aa38eafc02a",
            "name": "Cash Saver Pharmacy",
            "start_time": "09:01:00",
            "end_time": "12:43:00"
        },
        {
            "pharmaciesUUID": "b56bd56c-e1b2-4d0a-b281-8f58e9da4851",
            "name": "Pill Pack",
            "start_time": "01:39:00",
            "end_time": "16:59:00"
        },
    ]
}
```




  ### 3. List all pharmacies that are open on a day of the week, at a certain time
##### POST URL
  `http://bibleline2.herokuapp.com/phantom_mask/src/html/api/openingByWeekday/index.php`
##### Request json
   ```json
{
	"token": "{{TOKEN}}",
	"data":{
		 "weekDay" : "Mon"
	}
}
```

##### Description
   * token : Token obtained by login
   * data
     * weekDay : search day.(Mon or Tue or Wed or Thu or Fri or Sat or Sun)


##### Response
   ```json
{
  "error": false,
	"msg": "",
    "data": [
        {
            "pharmaciesUUID": "e04ad3da-d0a9-4a74-af32-7c32363cd735",
            "name": "Better You",
            "start_time": "12:56:00",
            "end_time": "21:58:00"
        },
        {
            "pharmaciesUUID": "90eb0d71-9fb7-4d92-a365-3aa38eafc02a",
            "name": "Cash Saver Pharmacy",
            "start_time": "11:00:00",
            "end_time": "14:48:00"
        },
    ]
}
```
### 4. List all masks that are sold by a given pharmacy, sorted by mask name or mask price
##### POST URL:
  `http://bibleline2.herokuapp.com/phantom_mask/src/html/api/maskItemByPharmacies/index.php`
##### Request json
   ```json
{
	"token": "{{TOKEN}}",
	"data":{
		 "pharmaciesUUID" : "1adc6ab2-afbe-4428-a7e5-ca28c593969b" ,
		 "sort"  : "name"
	}
}
```

##### Description
   * token : Token obtained by login
   * data
     * pharmaciesUUID : search pharmacies UUID.
	 * sort: sort by.('name' or 'price')

##### Response json
   ```json
{
    "error": false,
    "data": [
        {
            "maskUUID": "02fae9b0-3f23-4fd5-90ee-e993360b8079",
            "name": "AniMask (green) (10 per pack)",
            "price": "49.21"
        },
        {
            "maskUUID": "91dd9133-d106-42dc-ae74-841ef2507fa9",
            "name": "Free to Roam (black) (3 per pack)",
            "price": "13.83"
        },
        {
            "maskUUID": "6bbaec7f-a363-4ab5-8b52-d2156cacca61",
            "name": "Masquerade (blue) (6 per pack)",
            "price": "16.75"
        },
        {
            "maskUUID": "a3b4a5d9-2a26-4dd0-8378-8cdc97f42005",
            "name": "Pill Pack12 (black) (10 per pack)",
            "price": "14.9"
        }
    ]
}
```


### 5. List all pharmacies that have more or less than x mask products within a price range
##### POST URL:
  `http://bibleline2.herokuapp.com/phantom_mask/src/html/api/maskPharmaciesByPriceRange/index.php`
##### Request json
```json
{
    "token": "{{TOKEN}}",
    "data":{
         "minPrice" : "20" ,
         "maxPrice"  : "30"
    }
}
```
##### Description
   * token : Token obtained by login.
   * data
     * minPrice : Price min value.
	 * maxPrice: Price max value.


##### Response json
   ```json
{
    "error": false,
    "data": [
        {
            "pharmaciesUUID": "058368c5-bacf-42e6-a1e1-756d01d48d06",
            "pharmaciesName": "Longhorn Pharmacy",
            "maskList": [
                {
                    "maskUUID": "93dd0361-69d3-4dbf-803a-186f5fee59bc",
                    "maskPrice": "20",
                    "maskName": "Masquerade (blue) (6 per pack)"
                },
                {
                    "maskUUID": "8d7b977c-e7e2-443e-979c-bb1ecc6a69b1",
                    "maskPrice": "21.67",
                    "maskName": "Masquerade (blue) (10 per pack)"
                }
            ]
        },
        {
            "pharmaciesUUID": "ac4ef717-a0ec-449e-bbc7-1188fcdf0c8c",
            "pharmaciesName": "Thrifty Way Pharmacy",
            "maskList": [
                {
                    "maskUUID": "61e5cb34-e337-42be-bdb3-87d4dbf50110",
                    "maskPrice": "20.16",
                    "maskName": "Free to Roam (black) (6 per pack)"
                },
                {
                    "maskUUID": "572f51bb-fa47-4651-9cc2-9e554dee7685",
                    "maskPrice": "25.42",
                    "maskName": "AniMask (green) (10 per pack)"
                }
            ]
        }
    ]
}
```


### 6. Search for pharmacies or masks by name, ranked by relevance to search term
  ##### POST URL:
  `http://bibleline2.herokuapp.com/phantom_mask/src/html/api/searchMaskPharmacies/index.php`
   ##### Request json
   ```json
{
	"token": "{{TOKEN}}",
	"data":{
		"condition" : "pharmacies" ,
		"keyword"  : "A"
	}
}
```


##### Description
   * token : Token obtained by login
   * data
     * condition : search condition(mask or pharmacies)
	 * keyword: search keyword

##### Response json
   ```json
condition is pharmacies:
{
    "error": false,
    "msg": "",
    "data": [
        {
            "pharmaciesUUID": "e1192a65-2aec-4431-ae75-bc17145d8416",
            "pharmaciesName": "Apotheco"
        },
        {
            "pharmaciesUUID": "e048ef2f-62f8-4374-930c-131483d10229",
            "pharmaciesName": "Assured Rx"
        }
    ]
}

condition is pharmacies:
{
    "error": false,
    "msg": "",
    "data": [
        {
            "maskName": "AniMask (black) (10 per pack)",
            "maskUUID": "aa77c871-ef66-46b3-951f-bd110faf10c0",
            "pharmaciesName": "Thrifty Way Pharmacy",
            "pharmaciesUUID": "ac4ef717-a0ec-449e-bbc7-1188fcdf0c8c"
        },
        {
            "maskName": "AniMask (black) (3 per pack)",
            "maskUUID": "275f9f61-f975-435c-955b-e93eeb5ad51a",
            "pharmaciesName": "PrecisionMed",
            "pharmaciesUUID": "8c32762c-0f1f-4021-bcd5-61200ccba9bf"
        },
        {
            "maskName": "AniMask (black) (3 per pack)",
            "maskUUID": "84c1e3d9-34d7-4668-a8d5-09cb74461702",
            "pharmaciesName": "Longhorn Pharmacy",
            "pharmaciesUUID": "058368c5-bacf-42e6-a1e1-756d01d48d06"
        }
    ]
}
```

### 7.The top x users by total transaction amount of masks within a date range
  ##### POST URL:
  `http://bibleline2.herokuapp.com/phantom_mask/src/html/api/sellTotalTopByDate/index.php`
   ##### Request json
```json
{
    "token": "{{TOKEN}}",
    "data":{
         "count" : "5" ,
         "startDate"  : "2021-01-01",
         "endDate"  : "2021-11-30"
    }
}
```
  #####Description
   * token : Token obtained by login.
   * data
     * count : Top x users.
	 * startDate: Search start date.
	 * startDate: Search end date.


   ##### Response json
```json
{
    "error": false,
    "msg": "",
    "data": [
        {
            "userName": "Mae Hill",
            "userUUID": "694254b3-5984-4121-b07b-239d31cfbc43",
            "total": "213.81"
        },
        {
            "userName": "Peggy Maxwell",
            "userUUID": "11dedce9-1551-455e-9bc4-0e15f78b90ee",
            "total": "180.17"
        }
    ]
}
```

### 8. The total amount of masks and dollar value of transactions that happened within a date range
  ##### POST URL:
`http://bibleline2.herokuapp.com/phantom_mask/src/html/api/sellTotalMaskTotalByDate/index.php`
   ##### Request json
```json
{
    "token": "{{TOKEN}}",
    "data":{
         "startDate"  : "2021-01-01",
         "endDate"  : "2021-01-10"
    }
}
```
  ##### Description
   * token : Token obtained by login.
   * data
	 * startDate: Search start date.
	 * startDate: Search end date.


  ##### Response json
```json
{
    "error": false,
    "msg": "",
    "data": {
        "maskTotal": "197",
        "amountTotal": "627.31"
    }
}
```



### 9. Edit pharmacy name, mask name, and mask price
  ##### POST URL
`http://bibleline2.herokuapp.com/phantom_mask/src/html/api/editPharmaciesAndMask/index.php`
   ##### Request json
```json
mask:
{
    "token": "{{TOKEN}}",
    "data":{
         "editType" : "mask" ,
         "uuid" : "a3b4a5d9-2a26-4dd0-8378-8cdc97f42005",
         "editData"  : {
             "name" : "Pill Pack12"
         }
    }
}

pharmacies:
{
    "token": "{{TOKEN}}",
    "data":{
         "editType" : "pharmacies" ,
         "uuid" : "a3b4a5d9-2a26-4dd0-8378-8cdc97f42005",
         "editData"  : {
             "name" : "Pill Pack12",
			 "price" : "10.5"
         }
    }
}
```

  ##### Description
   * token : Token obtained by login.
   * data
	 * editType: Edit item type(pharmacies or mask)
	 * uuid: Edit item type uuid.
	 * editData
	   *  name : Edit item name
	   *  price : Edit mask price( If the editType is a mask)


  ##### Response json
```json
{
    "error": false,
    "msg": ""
}
```


### 10. Remove a mask product from a pharmacy given by mask name
  ##### POST URL:
`http://bibleline2.herokuapp.com/phantom_mask/src/html/api/deleteMask/index.php`
   ##### Request json
```json
{
    "token": "{{TOKEN}}",
    "data":{
         "pharmaciesUUID" : "22e4bf47-c2c9-4cd9-b11f-93f8ebac89b6" ,
         "maskName" : "AniMask (blue) (10 per pack)"
    }
}
```
  ##### Description
   * token : Token obtained by login.
   * data
	 * pharmaciesUUID: The pharmacy uuid to which the mask belongs.
	 * maskName: Mask name.


  ##### Response json
```json
{
    "error": false,
    "msg": ""
}
```

### 11. Process a user purchases a mask from a pharmacy, and handle all relevant data changes in an atomic transaction
  ##### POST URL:
`http://bibleline2.herokuapp.com/phantom_mask/src/html/api/userBuyMask/index.php`
   ##### Request json
```json
{
    "token": "{{TOKEN}}",
    "data":{
         "userUUID" : "11dedce9-1551-455e-9bc4-0e15f78b90ee" ,
         "maskUUID"  : "feda033f-fa63-445d-826b-5d191604c82c"
    }
}
```
  ##### Description
   * token : Token obtained by login.
   * data
	 * userUUID: User uuid.
	 * maskUUID: Mask uuid.


  ##### Response json
```json
{
    "error": false,
    "msg": ""
}
```


## Import Data Commands (required)
   Create mysql data base table and import data command,database settings must be set in [conf.ini](https://github.com/pu758614/phantom_mask/blob/master/src/html/conf.ini) first.

  `sh src/html/ini_table_data.sh`

## Test Coverage Report(optional)
  check report [here](#test-coverage-reportoptional)

## Demo Site Url (optional)
  + [API Demo](http://bibleline2.herokuapp.com/phantom_mask/src/html/site/)
  + [View user data](http://bibleline2.herokuapp.com/phantom_mask/src/html/site/?action=user_data)
  + [View pharmacies data](http://bibleline2.herokuapp.com/phantom_mask/src/html/site/?action=pharmacies_data)
