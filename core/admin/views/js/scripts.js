document.querySelector('.sitemap-button').onclick = (e) => {
    e.preventDefault();
    Ajax({type: 'POST'})
        .then((res) => {
            console.log('SUCCESS: ' + res);
        })
        .catch((res) => {
            console.log('ERROR: ' + res);
        })
}
