function locatePage(page) {
    if (!(typeof page === 'string' || page instanceof String))
        return;

    location.replace(page);
}