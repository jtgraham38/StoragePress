(()=>{"use strict";var e,s={4:(e,s,t)=>{const r=window.wp.blocks,n=window.React,a=(window.wp.i18n,window.wp.blockEditor),o=window.wp.apiFetch;var l=t.n(o);const i=window.wp.element,{InspectorControls:u}=wp.blockEditor,{PanelBody:p}=wp.components,c=JSON.parse('{"UU":"storagepress/storage-unit-business-detail-block"}');(0,r.registerBlockType)(c.UU,{edit:function(e){const s={none:"Choose a Business Detail...",storagepress_name:"Business Name",storagepress_address:"Business Address",storagepress_phone:"Business Phone Number",storagepress_email:"Business Email",storagepress_rental_terms:"Business Rental Terms",storagepress_checks_payable_to:"Make Checks Payable To"},[t,r]=(0,i.useState)(s);return l()({path:"/storagepress/v1/business-details"}).then((e=>{delete e.storagepress_listing_page,e.none="Choose a Business Detail...",r(e)})),(0,n.createElement)(n.Fragment,null,(0,n.createElement)(u,null,(0,n.createElement)(p,null,(0,n.createElement)("label",null,"Choose a Business Detail"),(0,n.createElement)("select",{value:e.attributes.key,onChange:s=>{e.setAttributes({key:s.target.value})}},Object.entries(s).map((([e,s])=>(0,n.createElement)("option",{key:e,value:e},s)))))),(0,n.createElement)("div",{...(0,a.useBlockProps)()},t[e.attributes.key]))}})}},t={};function r(e){var n=t[e];if(void 0!==n)return n.exports;var a=t[e]={exports:{}};return s[e](a,a.exports,r),a.exports}r.m=s,e=[],r.O=(s,t,n,a)=>{if(!t){var o=1/0;for(p=0;p<e.length;p++){for(var[t,n,a]=e[p],l=!0,i=0;i<t.length;i++)(!1&a||o>=a)&&Object.keys(r.O).every((e=>r.O[e](t[i])))?t.splice(i--,1):(l=!1,a<o&&(o=a));if(l){e.splice(p--,1);var u=n();void 0!==u&&(s=u)}}return s}a=a||0;for(var p=e.length;p>0&&e[p-1][2]>a;p--)e[p]=e[p-1];e[p]=[t,n,a]},r.n=e=>{var s=e&&e.__esModule?()=>e.default:()=>e;return r.d(s,{a:s}),s},r.d=(e,s)=>{for(var t in s)r.o(s,t)&&!r.o(e,t)&&Object.defineProperty(e,t,{enumerable:!0,get:s[t]})},r.o=(e,s)=>Object.prototype.hasOwnProperty.call(e,s),(()=>{var e={57:0,350:0};r.O.j=s=>0===e[s];var s=(s,t)=>{var n,a,[o,l,i]=t,u=0;if(o.some((s=>0!==e[s]))){for(n in l)r.o(l,n)&&(r.m[n]=l[n]);if(i)var p=i(r)}for(s&&s(t);u<o.length;u++)a=o[u],r.o(e,a)&&e[a]&&e[a][0](),e[a]=0;return r.O(p)},t=globalThis.webpackChunkstorage_unit_business_detail_block=globalThis.webpackChunkstorage_unit_business_detail_block||[];t.forEach(s.bind(null,0)),t.push=s.bind(null,t.push.bind(t))})();var n=r.O(void 0,[350],(()=>r(4)));n=r.O(n)})();