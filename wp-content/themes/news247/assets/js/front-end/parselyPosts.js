import News247PostsFromApi from './postsFromApi';

class News247ParselyPosts extends News247PostsFromApi{

    constructor( selector = '', props = {} ){
        super( selector, props );
    }


    formatPosts( responseData ){
        console.log(responseData);

        let posts = Object.values( responseData.posts );

        if( !posts ){
            return [];
        }

        return posts;
    }


}

window.ParselyPosts = News247ParselyPosts;

