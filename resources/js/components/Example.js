import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Example extends Component {

    render() {
        return (
            <div className="container" onClick={handleClick}>
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Example Component</div>

                            <div className="card-body">
                                I'm an example component {this.props.name}!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

if (document.getElementById('example')) {
    ReactDOM.render(<Example name="Hola" />, document.getElementById('example'));
}
