import React, { Component } from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import Suggestions from './Suggestions'

const API_URL = '/api/nations';

class Search extends Component {
  state = {
    query: '',
    value: '',
    results: []
  }

  getInfo = () => {
    axios.get(`${API_URL}?prefix=${this.state.query}`)
      .then(({ data }) => {
        this.setState({
          results: data.nations
        });
    });
  }

  handleInputChange = (e) => {
    this.setState({
      query: e.target.value
    }, () => {
      if (this.state.query && this.state.query.length > 1) {
          this.getInfo();
      } else if (this.state.query.length === 0) {
          this.setState({ results: [] });
      } else if (!this.state.query) {
      }
    });
  }

  onClickValueHandler = (val) => {    
    this.setState({ value: val.target.title });
    this.setState({ query: val.target.title });
    this.setState({ results: [] });
  }

  render() {
    return (
        <div>
            <input 
                value={this.state.query} 
                onChange={this.handleInputChange} 
                id="nation" type="text" className="form-control" name="nation" required 
            />

            <Suggestions results={this.state.results} onClickValue={this.onClickValueHandler}/>
        </div>
    );
  }
};

export default Search
    
if (document.getElementById('NationSearch')) {
    ReactDOM.render(<Search />, document.getElementById('NationSearch'));
}
