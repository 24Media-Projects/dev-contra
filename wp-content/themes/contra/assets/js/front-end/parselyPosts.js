import ContraPostsFromApi from './postsFromApi';

class ContraParselyPosts extends ContraPostsFromApi{

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

window.ParselyPosts = ContraParselyPosts;

