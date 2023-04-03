/**
 * Reactjs code for teh dashboard widget
 * 
 */
//import React, {useState, useEffect} from 'react'

const DashboardWidget = () => {
    const [posts, setPosts] = React.useState([]);

    React.useEffect(() => {
        fetch('/wp-json/wp/v2/posts')
            .then(res => res.json())
            .then(data => setPosts(data));
    }, []);

    return (
        <div className="dashboard-widget">
            <h2>Latest Posts</h2>
            <ul>
                {posts.map(post => (
                    <li key={post.id}>{post.title.rendered}</li>
                ))}
            </ul>
        </div>
    );
}


ReactDOM.render(<DashboardWidget />, document.querySelector('#dashboard-widget-container'));
