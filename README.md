# COVID 19 Website
- A PHP web scraper that gathers the latest US (country and state)
  coronavirus cases, death toll and recovered population from federal
(CDC) and states (health department/authority) websites, and provide you
with the most-up-to-date statistics.

# Future improvements
- Auto refresh the website every minutes with the timer by the side
- Add PHP query string, (i.e.
  chus.mathcs.wilkes.edu/covid19/index.php?states=US&OR&PA)
- Auto detect the states based on the users IP address, make the user
  default page US + their corresponding states
- Allow user to select states (using Select2 API), make sure selection
  is strictly ordered, and display the states according to the order
that user selected the states.
- Add update button after the selection section, save user selections
  (and sequence) in the cookie and refresh the page.
- Use cookies to store user selection and preferences (time to refresh?)
