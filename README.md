# PushAlert Notifications for ProcessWire
## System requirements

* PHP 5.6.4 or greater
* An SSL Certificate installed and your website configured to use HTTPS protocol

## General information
This module enables you to send push notifications and receive information about sent notifications on your HTTPS ProcessWire website.

All kudos to the great support team at PushAlert and to all the ProcessWire developers who've helped me with this project.

## First steps
* Visit [https://pushalert.co]() and create an account. Free or paid is your choice. Be sure to learn the  limits on free accounts, including the number of messages sent and REST API usage.
* Log into your PushAlert account and download the sw.js and manifest.json files from the Integrate section. 
* **DO NOT** download the PushAlert javascript code. This module will insert your customised code into the web  page `<head>` section
* Make a note of the Website ID under Settings > Website  
* Make a note of the REST API key under Settings > REST API Key

## Installation
Installation is a per any ProcessWire module, ie:

* Download the zip file into your site/modules folder then expand the zip file. Next, login to ProcessWire > go to Modules > New tab and click "Refresh". You should see that three new modules were found. 
* Install from the ProcessWire modules directory
* Copy/paste the GitHub url into your Modules New tab

Installing the PushAlert module will automatically install the related FieldtypePushAlert and InputfieldPushAlert modules

Configure the module with your REST API Key and Website ID and save the configuration data

Go to the front end and confirm you see a bell icon in the bottom right corner. This default icon and can be changed in your PushAlert Dashboard.

Your website is now ready to accept PushAlert subscriptions, and of course, allow current subscribers to unsubscribe.

Click the icon and subscribe. You are now ready to receive notifications from your website!

## How to use in admin page edit


Next you need to prepare the ProcessWire admin side to send notifications. It's straighforward ProcessWire stuff, ie:

* Create a new field of type FieldtypePushAlert
* Add the new field to a template
* Edit a page using that template and you'll see your PushAlert widget. It has several inputs of which the following three are required in order to send your notification:

1. **Message Title**: text up to 64 characters
2. **Message Body**: text up to 192 characters
3. **Send on Publish**: Checkbox must be checked to send the notification once the page is published 

Your page must be **Unpublished** for the notification to, as it says, **Send on Publish**. This is an anti-spam feature to ensure you don't inadvertently flood your subscribers with notifications each time you save the page. You must conciously decide to send the message. It will go as soon as you publish the page and the checkbox will revert to unchecked.

**Optional fields**:

1. **URL to Icon**: by default, the notification will use the ProcessWire 'P'. Enter the full HTTPS URL here to an alternate image. Recommended size is 192px x 192px.
2. **Schedule send time**: date field with date and time picker. Your notification will go to PushAlert immediately the page is published. PushAlert will then use this date/time to schedule sending the notification to your subscribers.

The message statistics update each time you visit the page, eg:

```
Message Id: 19191919

Attempted: 3

Delivered: 3

Clicked: 0

CTR: 0%
```


## How to use via the API in your templates

Read the PushAlert REST API documentation!

Example code for sending a notification from within a ProcessWire page template:

```
    <?php namespace ProcessWire;
    
    	$pushAlert = $modules->get('PushAlert');
    	
    	$options = array (
            'title' => 'This is the title text',
            'message' => 'This is the message body text',
            'icon' => 'https://www.website.com/images/my-icon.png'
		);
		
		$message = $pushalert->send($options);
	?>      
            
```
*View the page on the frontend to trigger the PushAlert send feature.*

A more complex example - sending to subscribed users who have a specific role:

```
// Send an alert to all users with the role 'customer' who have subscribed to notifications while logged-in

$pushAlert = $modules->get('PushAlert');
$customers = $users->find('roles=customer');
if ($customers->count) {
    $subscribers = $pages->get("name=pushalert-endpoint")->children("pushalert_user_id=".$customers);
    if ($subscribers->count) {
        $subscriberIds = $subscribers->each('title'); // array
        $subscriberJson = json_encode($subscriberIds);
        $options = array (
        	'title' => 'Customer role only',
        	'message' => 'Well, customer role for now',
        	'subscribers' => $subscriberJson
        );
        $message = $pushAlert->send($options);
    }
}
```

### Request parameters


Parameter   |Type   |Description
---|---|---
title|string|**REQUIRED** This is the title of the notification. It's maximum length is restricted to 64 characters.
message|string|**REQUIRED** The message body, with a maximum length of 192 characters.
url|string|**OPTIONAL** URL of the target page where you want subscribers to land after clicking on the notification. By default will use the current ProcessWire page https url
icon|string|**OPTIONAL** URL of the icon (192x192 pixels recommended). The icon URL should be served over HTTPS. If not specified or not available over a secure connection, the default icon from account would be used.
large_image|string|**OPTIONAL** URL of the hero image (720x480 pixels recommended, 1.5 aspect ratio). The large image URL should be served over HTTPS. If not specified or not available over a secure connection, this will not be sent.
action1|array|**OPTIONAL** An array containing the title and URL to be used for the first CTA button in the notification. Both title and URL are required. The title can be of maximum 16 characters. If longer, the button will not be shown.
action2|array|**OPTIONAL** An array containing the title and URL to be used for the second CTA button in the notification. Both title and URL are required. The title can be of maximum 16 characters. If longer, the button will not be shown.
action1_attr|array|**OPTIONAL** An array containing the title and URL with attributes to be used for the second CTA button in the notification. Both title and URL are required. The title can be of maximum 16 characters. If longer, the button will not be shown. Please make sure that action2 (fallback) is also added or the button with attributes will not be shown.Eg. {"title":"Apply {{coupon_code}}", "url":"http://mystore.eu"}
audience_id|int|**OPTIONAL** ID of target audience, created via Audience Creator from Dashboard. Used for precise targeting.
subscriber|string|**OPTIONAL** To send a notification to a particular subscriber, use the subscriber ID with this endpoint.
subscribers|string|**OPTIONAL** To send a notification to a specific set of subscribers, you can include this parameter with an array of subscriber IDs in JSON format. Only one among subscriber or subscribers parameters can be used in a single endpoint call.
title_attr|string|**OPTIONAL** Notification title with custom attributes. This is an optional field, if attributes are not found, the title parameter will be used.
message_attr|string|**OPTIONAL** Notification message with custom attributes. If omitted or attributes not available then the message parameter will be used.
url_attr|string|**OPTIONAL** Notification URL with custom attributes. If omitted or attributes not available then the url parameter will be used.
expire_time|int|**OPTIONAL** Define the expiry time of a notification in seconds. By default it is set to 86400 seconds or 24 hours.
schedule_time|unix timestamp|**OPTIONAL** Define the time when you want the notification to be sent with a 10-digit unix timestamp.

*Please note: some features only available to PushAlert Premium & Platinum accounts.*

## Important
###M anifest.json
Each webpage may only have one manifest.json file. The PushAlert manifest.json contains your site's GCM Sender ID. Should your site already contain a manifest.json file, copy/paste this line from PushAlert's into your original file:
```
"gcm_sender_id": "999999999999",
```

### Service Worker
The PushAlert sw.js registers a service worker. Only one service worker may be registered per site and will cause problems if you register another. Contact PushAlert technical support for assistance.
### Web browser push notification support
Check at [https://caniuse.com/#feat=push-api]() for current browser support. The most notable is lack of support for Apple iOS devices due to Apple's poor support of service workers in general.

Notifications on MAC OSX in Chrome and Firefox work, but do not in Safari.
