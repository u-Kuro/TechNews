# Technology News Website

This is a website that provides users with the latest technology news from around the world. It has both user and admin sides.

## User Side

Users can view news in the following ways:

- Home page: The latest technology news will be displayed on the home page.
- By category: Users can select a specific category such as "Mobile Phones & Tablet", "Hardware", "Software", "Cybersecurity", and can add more.
- By author: Users can view articles written by a specific author.
- Search keywords: Users can search for articles based on keywords.
- Article details: Users can click on a news article and see some details about the article with a "see more" link that takes them to the original journal of the article.

## Admin Side

Administrators can manage the following on the admin side:

- Posts: Create, update and delete news articles.
- Categories: Manage the categories of posts.
- Users: Manage the users of the website, including changing user roles to give them authority as an admin.
- Footer: Customize the footer of the website.

## Automated API Retrieval

The website is integrated with newsapi.org and uses an automated API retrieval system. The system checks for new articles every hour and retrieves the latest news from various sources around the world. Currently, the system is limited to a maximum of 100 API requests per day, ensuring that the website is updated at least every 1 hour and 12 minutes.

## SMS Notifications

Users will be notified via SMS when new articles are added to the website. The website is integrated an SMS API (ClickSend) and to ensure that notifications are sent to users located in different parts of the world. The SMS notifications are sent within a few minutes of a new article being added to the website.

## Deployed Website URL

The deployed website can be found at https://futuretechnews.rf.gd.
