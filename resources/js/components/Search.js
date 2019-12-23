import React, { Component } from 'react'
import ReactDOM from 'react-dom';
import axios from 'axios'
import Suggestions from './Suggestions'

const API_URL = {
       'nation': '/api/nations',
       'country': '/api/countries',
       'city': '/api/cities'
    };

function Store(initialState = {}) {
}

var myStore = new Store();

class Search extends Component {
  state = {
    query: this.props.query ? this.props.query : '',
    type: (this.props.type && (API_URL[this.props.type] !== undefined)) ? this.props.type : 'nation',
    value: '',
    cssClass: this.props.cssClass ? this.props.cssClass : 'form-control',
    results: []
  }

  getInfo = () => {
    var url = `${API_URL[this.state.type]}?prefix=${this.state.query}`;
      
    if (typeof this.props.myStore.country !== 'undefined' && typeof this.props.myStore[this.props.type] === 'undefined' ) {
        url = url + `&country=${this.props.myStore.country}`;
    }
    axios.get(url)
      .then(({ data }) => {
        this.setState({
          results: data.result
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
    
    this.props.myStore[this.state.type] = val.target.title;
  }

  render() {
    return (
        <div>
            <input 
                value={this.state.query} 
                onChange={this.handleInputChange} 
                id={this.state.type} type="text" className={this.state.cssClass} name={this.state.type} 
                autoComplete="off"
            />

            <Suggestions results={this.state.results} onClickValue={this.onClickValueHandler}/>
        </div>
    );
  }
};

export default Search
    
if (document.getElementById('NationSearch')) {
    var value = document.getElementById('NationSearch').getAttribute('value');
    ReactDOM.render(<Search type="nation" query={ value } myStore = {myStore}/>, document.getElementById('NationSearch'));
}

if (document.getElementById('Search')) {
    var value = document.getElementById('Search').getAttribute('value');
    var searchtype = document.getElementById('Search').getAttribute('type');
    ReactDOM.render(<Search type={ searchtype } query={ value } myStore = {myStore}/>, document.getElementById('Search'));
}

if (document.getElementById('CountrySearch')) {
    var value = document.getElementById('CountrySearch').getAttribute('value');
    var cssClass = document.getElementById('CitySearch').getAttribute('cssClass');
    ReactDOM.render(<Search type="country"  query={ value } myStore = {myStore} cssClass={cssClass} />, document.getElementById('CountrySearch'));
}

if (document.getElementById('CitySearch')) {
    var value = document.getElementById('CitySearch').getAttribute('value');
    var cssClass = document.getElementById('CitySearch').getAttribute('cssClass');
    ReactDOM.render(<Search type="city"  query={ value } myStore = {myStore} cssClass={cssClass} />, document.getElementById('CitySearch'));
}
