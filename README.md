
  

# cs458Fall2022 - Los Portales Theatre App

  

Team Project for Mobile Application Development.

  

  

For this semester, we have built an app from scratch based on a website we did for CS472.

  

  

The repository for that project can be found here [Los Portales Theatre web](https://github.com/damiansh/Project-Theater).

  

  

However in this repository, under source code you can find the the updated code for the website, as we created APIs to interact with the android app.

  

  

A live version of the web can be found [here](https://portales-theatre.site/)

  

  

## Team members

  

Brian Elder - [brelder](https://github.com/brelder)

  

  

Pedro Damian Marta - [damiansh](https://github.com/damiansh)

  

  

Preston Feagan - [Wildboyz997](https://github.com/Wildboyz997)

  

  

Skyler Landess - [Desslogic](https://github.com/Desslogic)

  

  

## Final Source Code Documentation

  

The documentation for this app can be found [HERE](https://damiansh.github.io/enmu/CS458/LosPortalesTheatreApp/javadoc/com/example/losportalestheatre/package-summary.html)

  

## Installation of Project in Android Studio

  

1. With git, run in the command line:

```commandline
git clone https://github.com/damiansh/cs458Fall2022.git
```

2. In the local repository, locate the "source folder" and inside of it grab "LosPortalesTheatre" folder

  

3. Add the folder to your AndroidProjectsFolder or simply open the folder through Android Studio by browsing.

  

4. Let the gradle build and run

  

5. Click build.

  

## App Source Code

  

You can find [HERE](https://github.com/damiansh/cs458Fall2022/tree/main/source%20code/LosPortalesTheatre) the source code for the android app. This is the folder you need to import/open into android studio if you wish to compile the app yourself or make changes.

  
  

## Installation (App)

  

1. Go to the [releases page](https://github.com/damiansh/cs458Fall2022/releases) and grab the apk from the latest release.

  

2. Download the apk into your phone and allow to install from unknown sources

  

3. Install the app

  

## Dependencies

  

The following dependencies were used

```JAVA
implementation 'com.github.bumptech.glide:glide:4.14.2'
annotationProcessor 'com.github.bumptech.glide:compiler:4.14.2'
implementation "androidx.cardview:cardview:1.0.0"
implementation "androidx.swiperefreshlayout:swiperefreshlayout:1.1.0"
implementation 'com.github.androidmads:QRGenerator:1.0.1'
```
  

## Web Source Code

  

You can find [HERE](https://github.com/damiansh/cs458Fall2022/tree/main/source%20code/LosPortalesTheatreWeb) the source code for the website.

  

## Los Portales APIs

  

For this project, the following REST APIs to connect to https://portales-theatre.site/ were created:

  

  

<strong>1. Request Upcoming plays</strong> - https://portales-theatre.site/includes/api-upcoming.php

  

```JSON
It does not require any input
```

  

<strong>2. Register Customers</strong> - https://portales-theatre.site/includes/api-register.php

  

```JSON
JSON Input that this API accepts
{   
    "email":"customer email",
    "psw":"pasword chosen by the customer",
    "pswRepeat":"confirmation of the previous password",
    "fname":"First name of the customer",
    "lname":"Last name of the customer",
    "birthday":"year of birth of the user",
    "phone":"phone number"
}
```

  

<strong>3. Login customer</strong> - https://portales-theatre.site/includes/api-login.php

```JSON
JSON Input that this API accepts
{
"email":"email of the account to login into",
"psw":"passwor of the account"
}
```

  

<strong>4. Verify Customer Key</strong> - https://portales-theatre.site/includes/api-verify.php

  
```JSON
JSON Input that this API accepts
{
"key": "userID-userKEY"
}
```

  

<strong>5. Request Customer Cart</strong> - https://portales-theatre.site/includes/api-cart.php

```JSON
JSON Input that this API accepts

{
"key": "userID-userKEY"
}

```
  

<strong>6. Request Customer Transactions</strong> - https://portales-theatre.site/includes/api-transactions.php


```JSON
JSON Input that this API accepts

{
"key": "userID-userKEY"
}
```


<strong>7. Add tickets to the Customer's Cart</strong> - https://portales-theatre.site/includes/api-addToCart.php

  
```JSON
JSON Input that this API accepts
{
    "key": "userID-userKEY"
    "seats":[
            {
            "ticket_id": ticketID,
            "seat_number": seatNumber as int
            }
            ]
}
```

  
<strong>8. Checkout tickets in the Customer's Cart</strong> - https://portales-theatre.site/includes/api-checkout.php

  
```JSON
JSON Input that this API accepts
{
	"key": "userID-userKEY"
	"email": "customer email",
	"first_name": "customer name",
	"last_name": "customer last name",
	"total": cost as double,
	"seats": [
		 {
		"ticket_id": ticketid as int
		 }
		]
}
```

 
<strong>9. Delete tickets from the Customer's Cart</strong> - https://portales-theatre.site/includes/api-deleteFromCart.php

```JSON
JSON Input that this API accepts
{
"key": "userID-userKEY"
"ticket_id": ticketID as int
}
```

  

<strong>10. Request Transaction by Transaction ID</strong> - https://portales-theatre.site/includes/api-transaction.php

```JSON
JSON Input that this API accepts
{
"key": "userID-userKEY"
"transaction_id": transactionid as int
}
```


<strong>11. Request Play's Seats Information by Play ID</strong> - https://portales-theatre.site/includes/api-seats.php

  
```JSON
JSON Input that this API accepts
{
"playID": "20"
}
```
  

<strong>Note:</strong> By just clicking in the LINKS, the api won't work as most of these require a JSON as input to work. However, if you click in the Upcoming Plays, you will receive a JSON with all the upcoming plays in the database, as it is the onyl API which does not require any input to return information.