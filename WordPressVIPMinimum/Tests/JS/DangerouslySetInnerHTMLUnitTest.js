function MyComponent() {
  return <div SetInnerHTML={createMarkup()} />; // Ok - similarly-named function.
}
const dangerouslySetInnerHTML="foo"; // Ok - similarly-named constant.

function createMarkup() {
  return {__html: 'First &middot; Second'};
}
function MyComponent() {
  return <div dangerouslySetInnerHTML={createMarkup()} />; // Error.
}
