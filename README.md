# compress-numbers
Specialized library to compress a list of numbers for use in REST URLs

# Use case
When having multiple IDs in URLs and alternatives like using `POST` are not preferable this library can help you by compressing arrays of numbers.
The performance of compression depends on the types of numbers found in the array.

## Algorithm.
- Sort the numbers
- Replace consecutive numbers by ranges (`[200, 201, 202, 205]` becomes `[[200, 202], [205]]`).
- For each range find a factor that is a multiple of 100.

The end result looks like this: `2X0T2N5`
