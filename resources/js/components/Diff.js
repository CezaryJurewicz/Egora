import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { diff_match_patch } from 'diff-match-patch';
import 'diff-match-patch-line-and-word';

class Diff extends React.Component {
    
    API_IDEA = '/api/ideas';
       
    constructor(props) {
        super(props)
        
        this.state = {
            diffTo: '',
            diffId: props.ideaId ? props.ideaId : '',
            diffToId: props.diffToId ? props.diffToId : '',
            action: props.action ? props.action : '',
            inviteSuccess: false
        }
    }
    
    handleInputChange = (e) => {
        this.setState({
          diffToId: e.target.value
        });
    }
    
    diff = (text1, text2) => {
        var dmp = new diff_match_patch();
//        const d = dmp.diff_main(text1, text2);
//        dmp.diff_cleanupSemantic(d);
//        const result = dmp.diff_prettyHtml(d);
        
        const diffs = dmp.diff_wordMode(text1, text2);
        dmp.diff_cleanupSemantic(diffs);
        const result = dmp.diff_prettyHtml(diffs);
        
        return result.replace(/&para;/g, '').replace(/&amp;/g, '&').replace(/&quot;/g, '"');
    }
    
    diffClick = () => {
        
        if (this.state.diffToId.length === 0) {
            this.props.replace(this.props.oldText());
        } else if (Number.isInteger(parseInt(this.state.diffToId))){
            
            axios.get(this.API_IDEA + '/' + this.state.diffId)
                .then(re => {
                    if (re.status === 200 && re.data && re.data.idea && re.data.idea.content) {

                        axios.get(this.API_IDEA + '/' + this.state.diffToId)
                            .then(r => {
                                if (r.status === 200 && r.data && r.data.idea && r.data.idea.content) {
                                    this.setState({ diffTo: r.data.idea.content });
                                    this.props.replace(this.diff(this.state.diffTo, re.data.idea.content ));

                                } else {
                                    console.log(r.status);
                                }
                            })
                            .catch(r => {
                                console.error(r.data);
                            });
                    }
                });
        
        }
    }    

    render() {
        return (
                <div className="row">
                    <div className="col-8">
                        <div className="form-group row mb-0">
                            <div className="pl-3 align-self-center">Compare to idea:</div>
                            <input onChange={this.handleInputChange} type="input" id="compare" className="col-md-2 col-5 mr-2 ml-3 form-control form-control-sm" name="diffToId" value={this.state.diffToId}/>
                            <div className="col-2">
                                <div onClick={() => this.diffClick()} type="submit" className="btn btn-primary btn-sm">
                                    Display
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        )
    }
};

export default Diff;
    
var diffs = document.querySelectorAll("div.diff-text");
if (diffs) {
    diffs.forEach(function(e) {
        ReactDOM.render(<Diff />, e);
    });
}

    