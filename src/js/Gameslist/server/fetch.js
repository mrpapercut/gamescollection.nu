'use strict';

import fetch from 'node-fetch';
import assign from 'object-assign';
import qs from 'query-string';

const req = (action, params = {}, cb) => fetch([
	document.location.href,
	'api',
	action,
	params ? '/' : null,
	qs.stringify(params)
].join(''), {}).then((res, err) => {
	if (err) console.warn(err);
	else return res.json();
}).then(cb);

export const fetchAll = cb => req('/games/all', {}, cb);
export const fetchLatest = cb => req('/games/latest', {}, cb);
