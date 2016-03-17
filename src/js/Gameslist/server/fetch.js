'use strict';

import fetch from 'node-fetch';
import assign from 'object-assign';
import qs from 'query-string';

export const req = (params, cb) => fetchJsonp([
	document.location.href,
	'/php/api.php?',
	qs.stringify(params)
].join(''), {}).then((res, err) => {
	if (err) console.warn(err);
	else return res.json();
}).then(cb);

export const fetchAll = cb => req({all: true}, cb);
