>> SendWelcomeEmailFlow

When a `Customer` is `created`
I want to:
  - immediately
do:
  - send the email `Welcome`
  
>> PromptProductFeedbackFlow

When a `Purchase` is `created`
I want to:
  - wait `seven days`
check:
  - Has the customer left feedback for the product
yes:
  - Do nothing
no:
  - Send an email prompt
  
>> RemovePromptProductFeedbackWhenFeedbackHasBeenLeftFlow

When a `FeedbackLeft` event is fired
I want to:
  - immediately
yes:
  - Remove the customers `PromptProductFeedbackFlow` flow

>> PromptUserToComeBackFlow

When a `CustomerHasLoggedIn` event is fired
I want to:
  - wait one month
now:
  - Replace existing `PromptUserToComeBackFlow`
check:
  - Has the user logged in within the past X days
no:
  - Send the email `Come Back Soon`
  
>> SustainedServerMemoryUsageAlertFlow

When a `ServerUsingTooMuchRam` event is fired
I want to:
  - immediately
before:
  - Do not register if existing `SustainedServerMemoryUsageAlertFlow` exists
repeat:
  - Every [X] minutes for [y] times
check:
  
