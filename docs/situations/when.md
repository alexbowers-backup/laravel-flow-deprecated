## You can decide when to run a Flow

### Immediately

Run a job immediately. 

This is not tangibly any different than running a job currently, 
except that a record of it will be preserved in the flow database,
which may be useful for determining whether future flows should run.

Examples:

>> SendWelcomeEmailFlow

When a `Customer` is `created`
I want to:
  - immediately
do:
  - send the email `Welcome`
  
>> SendOrderConfirmationFlow

When an `Order` is `completed`
I want to:
  - immediately
do:
  - send the email `OrderComplete`
  - reduce the current stock level for order items
  
>> RemoveProductFeedbackPromptFlow

When a `FeedbackLeft` event is fired
I want to:
  - immediately
do:
  - Remove all `PromptProductFeedbackFlow` scheduled.

### Delay until

Run a job in the future.

This is where Flow really sets itself apart from the standard Laravel
Queue. In the standard Queue, you cannot run a job any further than 15 minutes in the future, 
depending on the Queue Driver. Database queues do allow for further delay, but SQS does not.

Flow will store your jobs in the database, and then schedule them for you automatically
when they are due to be ran as an ordinary job queue, so you can still use whatever queue
driver you want to use, with no restriction on delay or functionality.

Examples:

>> PromptProductFeedbackFlow

When a `Purchase` is `created`
I want to:
  - wait for `seven days`
check:
  - Has the customer left feedback yet for the product
no:
  - Send a prompt for the customer to leave feedback
  
>> PromptUserToComeBackFlow

When a `CustomerHasLoggedIn` event is fired
I want to:
  - wait one month
now:
  - Replace all existing `PromptUserToComeBackFlow` currently scheduled
check:
  - Has the user logged in within the past 7 days
no:
  - Send the email `ComeBackSoon`
  
