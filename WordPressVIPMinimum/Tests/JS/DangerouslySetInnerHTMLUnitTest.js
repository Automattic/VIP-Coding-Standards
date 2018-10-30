function MyComponent() {
  return <div SetInnerHTML={createMarkup()} />; // Ok - similarly-named function.
}

function createMarkup() {
  return {__html: 'First &middot; Second'};
}
function MyComponent() {
  return <div dangerouslySetInnerHTML={createMarkup()} />; // Warning.
}
