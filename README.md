# cs458Fall2022 - Los Portales Theatre App
Team Project for Mobile Application Development.

For this semester, we have build an app from scratch based on a website we did for CS472. 

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

## Installation 

1. Go to the [releases page](https://github.com/damiansh/cs458Fall2022/releases) and grab the apk from the latest release.
2. Download the apk into your phone and allow to install from unknown sources
3. Install the app

## App Source Code 
You can find [HERE](https://github.com/damiansh/cs458Fall2022/tree/main/source%20code/LosPortalesTheatre) the source code for the android app. This is the folder you need to import/open into android studio if you wish to compile the app yourself or make changes. 

## Web Source Code 
You can find [HERE](https://github.com/damiansh/cs458Fall2022/tree/main/source%20code/LosPortalesTheatreWeb) the source code for the website. 


## Los Portales APIs

For this project, the following REST APIs to connect to https://portales-theatre.site/ were created: 

<strong>1. Request Upcoming plays</strong> - https://portales-theatre.site/includes/api-upcoming.php

<strong>2. Register Customers</strong> - https://portales-theatre.site/includes/api-register.php

<strong>3. Login customer</strong> - https://portales-theatre.site/includes/api-login.php

<strong>4. Verify Customer Key</strong> - https://portales-theatre.site/includes/api-verify.php

<strong>5. Request Customer Cart</strong> - https://portales-theatre.site/includes/api-cart.php

<strong>6. Request Customer Transactions</strong> - https://portales-theatre.site/includes/api-transactions.php

<strong>7. Add tickets to the Customer's Cart</strong> - https://portales-theatre.site/includes/api-addToCart.php

<strong>8. Checkout tickets in the Customer's Cart</strong> - https://portales-theatre.site/includes/api-checkout.php

<strong>9. Delete tickets from the Customer's Cart</strong> - https://portales-theatre.site/includes/api-deleteFromCart.php

<strong>10. Request Transaction by Transaction ID</strong> - https://portales-theatre.site/includes/api-transaction.php

<strong>11. Request Play's Seats Information by Play ID</strong> - https://portales-theatre.site/includes/api-seats.php

<strong>Note:</strong> By just clicking in the LINKS, the api won't work as most of these require a JSON as input to work. However, if you click in the Upcoming Plays, you will receive a JSON with all the upcoming plays in the database, as it is the onyl API which does not require any input to return information.



