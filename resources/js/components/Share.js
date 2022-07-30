import React from 'react';
import ReactDOM from 'react-dom';
import { FacebookShareButton, TwitterShareButton } from "react-share";
import { FacebookIcon, TwitterIcon } from "react-share";

class Share extends React.Component {
    constructor(props) {
        super(props)
        
        var text = "I support this idea in Egora.\n"
                + "What do you think about it?\n"
                + "Will you support it?";
        
        this.state = {
            url: props.url ? props.url : '',
            title: props.title ? props.title : text,
            description: props.description ? props.description : '',
            text: text,
            hashtag: props.hashtag ? props.hashtag : '#Egora',
            hashtags: props.hashtags ? [props.hashtags] : ['Egora','democracy','philosophy','IntelligentDemocracy','InternationalLogicParty']
        }
    }
    
    render() {
        console.log(this.state.description);
        return (
            <div>
                <FacebookShareButton 
                url={this.state.url} 
                quote={this.state.quote} 
                hashtag={this.state.hashtag} 
                description={this.state.description} 
                className="share-button pr-3"
                >
                    <FacebookIcon size={28} borderRadius={8} round={false} />
                </FacebookShareButton>

                <TwitterShareButton 
                title={this.state.title} 
                url={this.state.url} 
                hashtags={this.state.hashtags}
                >
                    <TwitterIcon size={28} borderRadius={8} round={false} />
                </TwitterShareButton>
            </div>
        );
    }
}

export default Share;

var shares = document.querySelectorAll(".share");
if (shares) {
    shares.forEach(function(e) {
        var url = e.getAttribute('url');
        var title = e.getAttribute('title');
        var description = e.getAttribute('description');
        var hashtag = e.getAttribute('hashtag');
        
        ReactDOM.render(<Share url={url} title={title} description={description} hashtag={hashtag} />, e);
    });
};
