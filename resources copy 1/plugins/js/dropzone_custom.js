var pxUtil = function() {
    var t, e, i, n, r, o, a, s, l, c = [].slice,
        h = function(t, e) {
            function i() {
                this.constructor = t
            }
            for(var n in e) u.call(e, n) && (t[n] = e[n]);
            return i.prototype = e.prototype, t.prototype = new i, t.__super__ = e.prototype, t
        },
        u = {}.hasOwnProperty;
    s = function() {}, e = function() {
        function t() {}
        return t.prototype.addEventListener = t.prototype.on, t.prototype.on = function(t, e) {
            return this._callbacks = this._callbacks || {}, this._callbacks[t] || (this._callbacks[t] = []), this._callbacks[t].push(e), this
        }, t.prototype.emit = function() {
            var t, e, i, n, r;
            if(i = arguments[0], t = 2 <= arguments.length ? c.call(arguments, 1) : [], this._callbacks = this._callbacks || {}, e = this._callbacks[i])
                for(n = 0, r = e.length; n < r; n++) e[n].apply(this, t);
            return this
        }, t.prototype.removeListener = t.prototype.off, t.prototype.removeAllListeners = t.prototype.off, t.prototype.removeEventListener = t.prototype.off, t.prototype.off = function(t, e) {
            var i, n, r, o;
            if(!this._callbacks || 0 === arguments.length) return this._callbacks = {}, this;
            if(!(i = this._callbacks[t])) return this;
            if(1 === arguments.length) return delete this._callbacks[t], this;
            for(n = r = 0, o = i.length; r < o; n = ++r)
                if(i[n] === e) {
                    i.splice(n, 1);
                    break
                } return this
        }, t
    }(), (t = function(t) {
        function n(t, e) {
            var i, o, a;
            if(this.element = t, this.version = n.version, this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, ""), this.clickableElements = [], this.listeners = [], this.files = [], "string" == typeof this.element && (this.element = document.querySelector(this.element)), !this.element || null == this.element.nodeType) throw new Error("Invalid dropzone element.");
            if(this.element.dropzone) throw new Error("Dropzone already attached.");
            if(n.instances.push(this), this.element.dropzone = this, i = null != (a = n.optionsForElement(this.element)) ? a : {}, this.options = r({}, this.defaultOptions, i, null != e ? e : {}), this.options.forceFallback || !n.isBrowserSupported()) return this.options.fallback.call(this);
            if(null == this.options.url && (this.options.url = this.element.getAttribute("action")), !this.options.url) throw new Error("No URL provided.");
            if(this.options.acceptedFiles && this.options.acceptedMimeTypes) throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
            this.options.acceptedMimeTypes && (this.options.acceptedFiles = this.options.acceptedMimeTypes, delete this.options.acceptedMimeTypes), null != this.options.renameFilename && (this.options.renameFile = function(t) {
                return function(e) {
                    return t.options.renameFilename.call(t, e.name, e)
                }
            }(this)), this.options.method = this.options.method.toUpperCase(), (o = this.getExistingFallback()) && o.parentNode && o.parentNode.removeChild(o), !1 !== this.options.previewsContainer && (this.options.previewsContainer ? this.previewsContainer = n.getElement(this.options.previewsContainer, "previewsContainer") : this.previewsContainer = this.element), this.options.clickable && (!0 === this.options.clickable ? this.clickableElements = [this.element] : this.clickableElements = n.getElements(this.options.clickable, "clickable")), this.init()
        }
        var r, o;
        return h(n, e), n.prototype.Emitter = e, n.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "addedfile", "addedfiles", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded", "maxfilesreached", "queuecomplete"], n.prototype.defaultOptions = {
            url: null,
            method: "post",
            withCredentials: !1,
            timeout: 3e4,
            parallelUploads: 2,
            uploadMultiple: !1,
            maxFilesize: 256,
            paramName: "file",
            createImageThumbnails: !0,
            maxThumbnailFilesize: 10,
            thumbnailWidth: 120,
            thumbnailHeight: 120,
            thumbnailMethod: "crop",
            resizeWidth: null,
            resizeHeight: null,
            resizeMimeType: null,
            resizeQuality: .8,
            resizeMethod: "contain",
            filesizeBase: 1e3,
            maxFiles: null,
            params: {},
            headers: null,
            clickable: !0,
            ignoreHiddenFiles: !0,
            acceptedFiles: null,
            acceptedMimeTypes: null,
            autoProcessQueue: !0,
            autoQueue: !0,
            addRemoveLinks: !1,
            previewsContainer: null,
            hiddenInputContainer: "body",
            capture: null,
            renameFilename: null,
            renameFile: null,
            forceFallback: !1,
            dictDefaultMessage: "Drop files here to upload",
            dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
            dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
            dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
            dictInvalidFileType: "You can't upload files of this type.",
            dictResponseError: "Server responded with {{statusCode}} code.",
            dictCancelUpload: "Cancel upload",
            dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
            dictRemoveFile: "Remove file",
            dictRemoveFileConfirmation: null,
            dictMaxFilesExceeded: "You can not upload any more files.",
            dictFileSizeUnits: {
                tb: "TB",
                gb: "GB",
                mb: "MB",
                kb: "KB",
                b: "byte"
            },
            init: function() {
                return s
            },
            accept: function(t, e) {
                return e()
            },
            fallback: function() {
                var t, e, i, r, o, a;
                for(this.element.className = this.element.className + " dz-browser-not-supported", e = 0, i = (o = this.element.getElementsByTagName("div")).length; e < i; e++) /(^| )dz-message($| )/.test((t = o[e]).className) && (r = t, t.className = "dz-message");
                return r || (r = n.createElement('<div class="dz-message"><span></span></div>'), this.element.appendChild(r)), (a = r.getElementsByTagName("span")[0]) && (null != a.textContent ? a.textContent = this.options.dictFallbackMessage : null != a.innerText && (a.innerText = this.options.dictFallbackMessage)), this.element.appendChild(this.getFallbackForm())
            },
            resize: function(t, e, i, n) {
                var r, o, a;
                if(r = {
                        srcX: 0,
                        srcY: 0,
                        srcWidth: t.width,
                        srcHeight: t.height
                    }, o = t.width / t.height, null == e && null == i ? (e = r.srcWidth, i = r.srcHeight) : null == e ? e = i * o : null == i && (i = e / o), e = Math.min(e, r.srcWidth), i = Math.min(i, r.srcHeight), a = e / i, r.srcWidth > e || r.srcHeight > i)
                    if("crop" === n) o > a ? (r.srcHeight = t.height, r.srcWidth = r.srcHeight * a) : (r.srcWidth = t.width, r.srcHeight = r.srcWidth / a);
                    else {
                        if("contain" !== n) throw new Error("Unknown resizeMethod '" + n + "'");
                        o > a ? i = e / o : e = i * o
                    } return r.srcX = (t.width - r.srcWidth) / 2, r.srcY = (t.height - r.srcHeight) / 2, r.trgWidth = e, r.trgHeight = i, r
            },
            transformFile: function(t, e) {
                return (this.options.resizeWidth || this.options.resizeHeight) && t.type.match(/image.*/) ? this.resizeImage(t, this.options.resizeWidth, this.options.resizeHeight, this.options.resizeMethod, e) : e(t)
            },
            previewTemplate: '<div class="dz-preview dz-file-preview">\n  <div class="dz-image"><img data-dz-thumbnail /></div>\n  <div class="dz-details">\n    <div class="dz-size"><span data-dz-size></span></div>\n    <div class="dz-filename"><span data-dz-name></span></div>\n  </div>\n  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\n  <div class="dz-error-message"><span data-dz-errormessage></span></div>\n  <div class="dz-success-mark">\n    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\n      <title>Check</title>\n      <defs></defs>\n      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\n        <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>\n      </g>\n    </svg>\n  </div>\n  <div class="dz-error-mark">\n    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\n      <title>Error</title>\n      <defs></defs>\n      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\n        <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">\n          <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>',
            drop: function(t) {
                return this.element.classList.remove("dz-drag-hover")
            },
            dragstart: s,
            dragend: function(t) {
                return this.element.classList.remove("dz-drag-hover")
            },
            dragenter: function(t) {
                return this.element.classList.add("dz-drag-hover")
            },
            dragover: function(t) {
                return this.element.classList.add("dz-drag-hover")
            },
            dragleave: function(t) {
                return this.element.classList.remove("dz-drag-hover")
            },
            paste: s,
            reset: function() {
                return this.element.classList.remove("dz-started")
            },
            addedfile: function(t) {
                var e, i, r, o, a, s, l, c, h, u, d, f;
                if(this.element === this.previewsContainer && this.element.classList.add("dz-started"), this.previewsContainer) {
                    for(t.previewElement = n.createElement(this.options.previewTemplate.trim()), t.previewTemplate = t.previewElement, this.previewsContainer.appendChild(t.previewElement), e = 0, o = (l = t.previewElement.querySelectorAll("[data-dz-name]")).length; e < o; e++) l[e].textContent = t.name;
                    for(i = 0, a = (c = t.previewElement.querySelectorAll("[data-dz-size]")).length; i < a; i++) c[i].innerHTML = this.filesize(t.size);
                    for(this.options.addRemoveLinks && (t._removeLink = n.createElement('<a class="dz-remove" href="javascript:undefined;" data-dz-remove>' + this.options.dictRemoveFile + "</a>"), t.previewElement.appendChild(t._removeLink)), u = function(e) {
                            return function(i) {
                                return i.preventDefault(), i.stopPropagation(), t.status === n.UPLOADING ? n.confirm(e.options.dictCancelUploadConfirmation, function() {
                                    return e.removeFile(t)
                                }) : e.options.dictRemoveFileConfirmation ? n.confirm(e.options.dictRemoveFileConfirmation, function() {
                                    return e.removeFile(t)
                                }) : e.removeFile(t)
                            }
                        }(this), f = [], r = 0, s = (h = t.previewElement.querySelectorAll("[data-dz-remove]")).length; r < s; r++) d = h[r], f.push(d.addEventListener("click", u));
                    return f
                }
            },
            removedfile: function(t) {
                var e;
                return t.previewElement && null != (e = t.previewElement) && e.parentNode.removeChild(t.previewElement), this._updateMaxFilesReachedClass()
            },
            thumbnail: function(t, e) {
                var i, n, r, o;
                if(t.previewElement) {
                    for(t.previewElement.classList.remove("dz-file-preview"), i = 0, n = (r = t.previewElement.querySelectorAll("[data-dz-thumbnail]")).length; i < n; i++)(o = r[i]).alt = t.name, o.src = e;
                    return setTimeout(function() {
                        return t.previewElement.classList.add("dz-image-preview")
                    }, 1)
                }
            },
            error: function(t, e) {
                var i, n, r, o, a;
                if(t.previewElement) {
                    for(t.previewElement.classList.add("dz-error"), "String" != typeof e && e.error && (e = e.error), a = [], i = 0, n = (o = t.previewElement.querySelectorAll("[data-dz-errormessage]")).length; i < n; i++) r = o[i], a.push(r.textContent = e);
                    return a
                }
            },
            errormultiple: s,
            processing: function(t) {
                if(t.previewElement && (t.previewElement.classList.add("dz-processing"), t._removeLink)) return t._removeLink.textContent = this.options.dictCancelUpload
            },
            processingmultiple: s,
            uploadprogress: function(t, e, i) {
                var n, r, o, a, s;
                if(t.previewElement) {
                    for(s = [], n = 0, r = (a = t.previewElement.querySelectorAll("[data-dz-uploadprogress]")).length; n < r; n++) "PROGRESS" === (o = a[n]).nodeName ? s.push(o.value = e) : s.push(o.style.width = e + "%");
                    return s
                }
            },
            totaluploadprogress: s,
            sending: s,
            sendingmultiple: s,
            success: function(t) {
                if(t.previewElement) return t.previewElement.classList.add("dz-success")
            },
            successmultiple: s,
            canceled: function(t) {
                return this.emit("error", t, "Upload canceled.")
            },
            canceledmultiple: s,
            complete: function(t) {
                if(t._removeLink && (t._removeLink.textContent = this.options.dictRemoveFile), t.previewElement) return t.previewElement.classList.add("dz-complete")
            },
            completemultiple: s,
            maxfilesexceeded: s,
            maxfilesreached: s,
            queuecomplete: s,
            addedfiles: s
        }, r = function() {
            var t, e, i, n, r, o, a;
            for(o = arguments[0], t = 0, i = (r = 2 <= arguments.length ? c.call(arguments, 1) : []).length; t < i; t++) {
                n = r[t];
                for(e in n) a = n[e], o[e] = a
            }
            return o
        }, n.prototype.getAcceptedFiles = function() {
            var t, e, i, n, r;
            for(r = [], e = 0, i = (n = this.files).length; e < i; e++)(t = n[e]).accepted && r.push(t);
            return r
        }, n.prototype.getRejectedFiles = function() {
            var t, e, i, n, r;
            for(r = [], e = 0, i = (n = this.files).length; e < i; e++)(t = n[e]).accepted || r.push(t);
            return r
        }, n.prototype.getFilesWithStatus = function(t) {
            var e, i, n, r, o;
            for(o = [], i = 0, n = (r = this.files).length; i < n; i++)(e = r[i]).status === t && o.push(e);
            return o
        }, n.prototype.getQueuedFiles = function() {
            return this.getFilesWithStatus(n.QUEUED)
        }, n.prototype.getUploadingFiles = function() {
            return this.getFilesWithStatus(n.UPLOADING)
        }, n.prototype.getAddedFiles = function() {
            return this.getFilesWithStatus(n.ADDED)
        }, n.prototype.getActiveFiles = function() {
            var t, e, i, r, o;
            for(o = [], e = 0, i = (r = this.files).length; e < i; e++)(t = r[e]).status !== n.UPLOADING && t.status !== n.QUEUED || o.push(t);
            return o
        }, n.prototype.init = function() {
            var t, e, i, r, o, a, s;
            for("form" === this.element.tagName && this.element.setAttribute("enctype", "multipart/form-data"), this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message") && this.element.appendChild(n.createElement('<div class="dz-default dz-message"><span>' + this.options.dictDefaultMessage + "</span></div>")), this.clickableElements.length && (s = function(t) {
                    return function() {
                        return t.hiddenFileInput && t.hiddenFileInput.parentNode.removeChild(t.hiddenFileInput), t.hiddenFileInput = document.createElement("input"), t.hiddenFileInput.setAttribute("type", "file"), (null == t.options.maxFiles || t.options.maxFiles > 1) && t.hiddenFileInput.setAttribute("multiple", "multiple"), t.hiddenFileInput.className = "dz-hidden-input", null != t.options.acceptedFiles && t.hiddenFileInput.setAttribute("accept", t.options.acceptedFiles), null != t.options.capture && t.hiddenFileInput.setAttribute("capture", t.options.capture), t.hiddenFileInput.style.visibility = "hidden", t.hiddenFileInput.style.position = "absolute", t.hiddenFileInput.style.top = "0", t.hiddenFileInput.style.left = "0", t.hiddenFileInput.style.height = "0", t.hiddenFileInput.style.width = "0", document.querySelector(t.options.hiddenInputContainer).appendChild(t.hiddenFileInput), t.hiddenFileInput.addEventListener("change", function() {
                            var e, i, n, r;
                            if((i = t.hiddenFileInput.files).length)
                                for(n = 0, r = i.length; n < r; n++) e = i[n], t.addFile(e);
                            return t.emit("addedfiles", i), s()
                        })
                    }
                }(this))(), this.URL = null != (o = window.URL) ? o : window.webkitURL, e = 0, i = (a = this.events).length; e < i; e++) t = a[e], this.on(t, this.options[t]);
            return this.on("uploadprogress", function(t) {
                return function() {
                    return t.updateTotalUploadProgress()
                }
            }(this)), this.on("removedfile", function(t) {
                return function() {
                    return t.updateTotalUploadProgress()
                }
            }(this)), this.on("canceled", function(t) {
                return function(e) {
                    return t.emit("complete", e)
                }
            }(this)), this.on("complete", function(t) {
                return function(e) {
                    if(0 === t.getAddedFiles().length && 0 === t.getUploadingFiles().length && 0 === t.getQueuedFiles().length) return setTimeout(function() {
                        return t.emit("queuecomplete")
                    }, 0)
                }
            }(this)), r = function(t) {
                return t.stopPropagation(), t.preventDefault ? t.preventDefault() : t.returnValue = !1
            }, this.listeners = [{
                element: this.element,
                events: {
                    dragstart: function(t) {
                        return function(e) {
                            return t.emit("dragstart", e)
                        }
                    }(this),
                    dragenter: function(t) {
                        return function(e) {
                            return r(e), t.emit("dragenter", e)
                        }
                    }(this),
                    dragover: function(t) {
                        return function(e) {
                            var i;
                            try {
                                i = e.dataTransfer.effectAllowed
                            } catch (t) {}
                            return e.dataTransfer.dropEffect = "move" === i || "linkMove" === i ? "move" : "copy", r(e), t.emit("dragover", e)
                        }
                    }(this),
                    dragleave: function(t) {
                        return function(e) {
                            return t.emit("dragleave", e)
                        }
                    }(this),
                    drop: function(t) {
                        return function(e) {
                            return r(e), t.drop(e)
                        }
                    }(this),
                    dragend: function(t) {
                        return function(e) {
                            return t.emit("dragend", e)
                        }
                    }(this)
                }
            }], this.clickableElements.forEach(function(t) {
                return function(e) {
                    return t.listeners.push({
                        element: e,
                        events: {
                            click: function(i) {
                                return (e !== t.element || i.target === t.element || n.elementInside(i.target, t.element.querySelector(".dz-message"))) && t.hiddenFileInput.click(), !0
                            }
                        }
                    })
                }
            }(this)), this.enable(), this.options.init.call(this)
        }, n.prototype.destroy = function() {
            var t;
            return this.disable(), this.removeAllFiles(!0), (null != (t = this.hiddenFileInput) ? t.parentNode : void 0) && (this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput), this.hiddenFileInput = null), delete this.element.dropzone, n.instances.splice(n.instances.indexOf(this), 1)
        }, n.prototype.updateTotalUploadProgress = function() {
            var t, e, i, n, r, o, a;
            if(o = 0, r = 0, this.getActiveFiles().length) {
                for(e = 0, i = (n = this.getActiveFiles()).length; e < i; e++) o += (t = n[e]).upload.bytesSent, r += t.upload.total;
                a = 100 * o / r
            } else a = 100;
            return this.emit("totaluploadprogress", a, r, o)
        }, n.prototype._getParamName = function(t) {
            return "function" == typeof this.options.paramName ? this.options.paramName(t) : this.options.paramName + (this.options.uploadMultiple ? "[" + t + "]" : "")
        }, n.prototype._renameFile = function(t) {
            return "function" != typeof this.options.renameFile ? t.name : this.options.renameFile(t)
        }, n.prototype.getFallbackForm = function() {
            var t, e, i, r;
            return (t = this.getExistingFallback()) ? t : (i = '<div class="dz-fallback">', this.options.dictFallbackText && (i += "<p>" + this.options.dictFallbackText + "</p>"), i += '<input type="file" name="' + this._getParamName(0) + '" ' + (this.options.uploadMultiple ? 'multiple="multiple"' : void 0) + ' /><input type="submit" value="Upload!"></div>', e = n.createElement(i), "FORM" !== this.element.tagName ? (r = n.createElement('<form action="' + this.options.url + '" enctype="multipart/form-data" method="' + this.options.method + '"></form>')).appendChild(e) : (this.element.setAttribute("enctype", "multipart/form-data"), this.element.setAttribute("method", this.options.method)), null != r ? r : e)
        }, n.prototype.getExistingFallback = function() {
            var t, e, i, n, r, o;
            for(e = function(t) {
                    var e, i, n;
                    for(i = 0, n = t.length; i < n; i++)
                        if(e = t[i], /(^| )fallback($| )/.test(e.className)) return e
                }, i = 0, n = (r = ["div", "form"]).length; i < n; i++)
                if(o = r[i], t = e(this.element.getElementsByTagName(o))) return t
        }, n.prototype.setupEventListeners = function() {
            var t, e, i, n, r, o, a;
            for(a = [], i = 0, n = (o = this.listeners).length; i < n; i++) t = o[i], a.push(function() {
                var i, n;
                i = t.events, n = [];
                for(e in i) r = i[e], n.push(t.element.addEventListener(e, r, !1));
                return n
            }());
            return a
        }, n.prototype.removeEventListeners = function() {
            var t, e, i, n, r, o, a;
            for(a = [], i = 0, n = (o = this.listeners).length; i < n; i++) t = o[i], a.push(function() {
                var i, n;
                i = t.events, n = [];
                for(e in i) r = i[e], n.push(t.element.removeEventListener(e, r, !1));
                return n
            }());
            return a
        }, n.prototype.disable = function() {
            var t, e, i, n, r;
            for(this.clickableElements.forEach(function(t) {
                    return t.classList.remove("dz-clickable")
                }), this.removeEventListeners(), r = [], e = 0, i = (n = this.files).length; e < i; e++) t = n[e], r.push(this.cancelUpload(t));
            return r
        }, n.prototype.enable = function() {
            return this.clickableElements.forEach(function(t) {
                return t.classList.add("dz-clickable")
            }), this.setupEventListeners()
        }, n.prototype.filesize = function(t) {
            var e, i, n, r, o, a, s, l;
            if(o = 0, a = "b", t > 0) {
                for(i = n = 0, r = (l = ["tb", "gb", "mb", "kb", "b"]).length; n < r; i = ++n)
                    if(s = l[i], e = Math.pow(this.options.filesizeBase, 4 - i) / 10, t >= e) {
                        o = t / Math.pow(this.options.filesizeBase, 4 - i), a = s;
                        break
                    } o = Math.round(10 * o) / 10
            }
            return "<strong>" + o + "</strong> " + this.options.dictFileSizeUnits[a]
        }, n.prototype._updateMaxFilesReachedClass = function() {
            return null != this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? (this.getAcceptedFiles().length === this.options.maxFiles && this.emit("maxfilesreached", this.files), this.element.classList.add("dz-max-files-reached")) : this.element.classList.remove("dz-max-files-reached")
        }, n.prototype.drop = function(t) {
            var e, i;
            t.dataTransfer && (this.emit("drop", t), e = t.dataTransfer.files, this.emit("addedfiles", e), e.length && ((i = t.dataTransfer.items) && i.length && null != i[0].webkitGetAsEntry ? this._addFilesFromItems(i) : this.handleFiles(e)))
        }, n.prototype.paste = function(t) {
            var e, i;
            if(null != (null != t && null != (i = t.clipboardData) ? i.items : void 0)) return this.emit("paste", t), (e = t.clipboardData.items).length ? this._addFilesFromItems(e) : void 0
        }, n.prototype.handleFiles = function(t) {
            var e, i, n, r;
            for(r = [], i = 0, n = t.length; i < n; i++) e = t[i], r.push(this.addFile(e));
            return r
        }, n.prototype._addFilesFromItems = function(t) {
            var e, i, n, r, o;
            for(o = [], n = 0, r = t.length; n < r; n++) null != (i = t[n]).webkitGetAsEntry && (e = i.webkitGetAsEntry()) ? e.isFile ? o.push(this.addFile(i.getAsFile())) : e.isDirectory ? o.push(this._addFilesFromDirectory(e, e.name)) : o.push(void 0) : null != i.getAsFile && (null == i.kind || "file" === i.kind) ? o.push(this.addFile(i.getAsFile())) : o.push(void 0);
            return o
        }, n.prototype._addFilesFromDirectory = function(t, e) {
            var i, n, r;
            return i = t.createReader(), n = function(t) {
                return "undefined" != typeof console && null !== console && "function" == typeof console.log ? console.log(t) : void 0
            }, (r = function(t) {
                return function() {
                    return i.readEntries(function(i) {
                        var n, o, a;
                        if(i.length > 0) {
                            for(o = 0, a = i.length; o < a; o++)(n = i[o]).isFile ? n.file(function(i) {
                                if(!t.options.ignoreHiddenFiles || "." !== i.name.substring(0, 1)) return i.fullPath = e + "/" + i.name, t.addFile(i)
                            }) : n.isDirectory && t._addFilesFromDirectory(n, e + "/" + n.name);
                            r()
                        }
                        return null
                    }, n)
                }
            }(this))()
        }, n.prototype.accept = function(t, e) {
            return t.size > 1024 * this.options.maxFilesize * 1024 ? e(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(t.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize)) : n.isValidFile(t, this.options.acceptedFiles) ? null != this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? (e(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles)), this.emit("maxfilesexceeded", t)) : this.options.accept.call(this, t, e) : e(this.options.dictInvalidFileType)
        }, n.prototype.addFile = function(t) {
            return t.upload = {
                progress: 0,
                total: t.size,
                bytesSent: 0,
                filename: this._renameFile(t)
            }, this.files.push(t), t.status = n.ADDED, this.emit("addedfile", t), this._enqueueThumbnail(t), this.accept(t, function(e) {
                return function(i) {
                    return i ? (t.accepted = !1, e._errorProcessing([t], i)) : (t.accepted = !0, e.options.autoQueue && e.enqueueFile(t)), e._updateMaxFilesReachedClass()
                }
            }(this))
        }, n.prototype.enqueueFiles = function(t) {
            var e, i, n;
            for(i = 0, n = t.length; i < n; i++) e = t[i], this.enqueueFile(e);
            return null
        }, n.prototype.enqueueFile = function(t) {
            if(t.status !== n.ADDED || !0 !== t.accepted) throw new Error("This file can't be queued because it has already been processed or was rejected.");
            if(t.status = n.QUEUED, this.options.autoProcessQueue) return setTimeout(function(t) {
                return function() {
                    return t.processQueue()
                }
            }(this), 0)
        }, n.prototype._thumbnailQueue = [], n.prototype._processingThumbnail = !1, n.prototype._enqueueThumbnail = function(t) {
            if(this.options.createImageThumbnails && t.type.match(/image.*/) && t.size <= 1024 * this.options.maxThumbnailFilesize * 1024) return this._thumbnailQueue.push(t), setTimeout(function(t) {
                return function() {
                    return t._processThumbnailQueue()
                }
            }(this), 0)
        }, n.prototype._processThumbnailQueue = function() {
            var t;
            if(!this._processingThumbnail && 0 !== this._thumbnailQueue.length) return this._processingThumbnail = !0, t = this._thumbnailQueue.shift(), this.createThumbnail(t, this.options.thumbnailWidth, this.options.thumbnailHeight, this.options.thumbnailMethod, !0, function(e) {
                return function(i) {
                    return e.emit("thumbnail", t, i), e._processingThumbnail = !1, e._processThumbnailQueue()
                }
            }(this))
        }, n.prototype.removeFile = function(t) {
            if(t.status === n.UPLOADING && this.cancelUpload(t), this.files = l(this.files, t), this.emit("removedfile", t), 0 === this.files.length) return this.emit("reset")
        }, n.prototype.removeAllFiles = function(t) {
            var e, i, r, o;
            for(null == t && (t = !1), i = 0, r = (o = this.files.slice()).length; i < r; i++)((e = o[i]).status !== n.UPLOADING || t) && this.removeFile(e);
            return null
        }, n.prototype.resizeImage = function(t, e, r, o, a) {
            return this.createThumbnail(t, e, r, o, !1, function(e) {
                return function(r, o) {
                    var s, l;
                    return null === o ? a(t) : (null == (s = e.options.resizeMimeType) && (s = t.type), l = o.toDataURL(s, e.options.resizeQuality), "image/jpeg" !== s && "image/jpg" !== s || (l = i.restore(t.dataURL, l)), a(n.dataURItoBlob(l)))
                }
            }(this))
        }, n.prototype.createThumbnail = function(t, e, i, n, r, o) {
            var a;
            return a = new FileReader, a.onload = function(s) {
                return function() {
                    t.dataURL = a.result; {
                        if("image/svg+xml" !== t.type) return s.createThumbnailFromUrl(t, e, i, n, r, o);
                        null != o && o(a.result)
                    }
                }
            }(this), a.readAsDataURL(t)
        }, n.prototype.createThumbnailFromUrl = function(t, e, i, n, r, o, s) {
            var l;
            return l = document.createElement("img"), s && (l.crossOrigin = s), l.onload = function(s) {
                return function() {
                    var c;
                    return c = function(t) {
                        return t(1)
                    }, "undefined" != typeof EXIF && null !== EXIF && r && (c = function(t) {
                        return EXIF.getData(l, function() {
                            return t(EXIF.getTag(this, "Orientation"))
                        })
                    }), c(function(r) {
                        var c, h, u, d, f, p, g, m;
                        switch (t.width = l.width, t.height = l.height, g = s.options.resize.call(s, t, e, i, n), c = document.createElement("canvas"), h = c.getContext("2d"), c.width = g.trgWidth, c.height = g.trgHeight, r > 4 && (c.width = g.trgHeight, c.height = g.trgWidth), r) {
                            case 2:
                                h.translate(c.width, 0), h.scale(-1, 1);
                                break;
                            case 3:
                                h.translate(c.width, c.height), h.rotate(Math.PI);
                                break;
                            case 4:
                                h.translate(0, c.height), h.scale(1, -1);
                                break;
                            case 5:
                                h.rotate(.5 * Math.PI), h.scale(1, -1);
                                break;
                            case 6:
                                h.rotate(.5 * Math.PI), h.translate(0, -c.height);
                                break;
                            case 7:
                                h.rotate(.5 * Math.PI), h.translate(c.width, -c.height), h.scale(-1, 1);
                                break;
                            case 8:
                                h.rotate(-.5 * Math.PI), h.translate(-c.width, 0)
                        }
                        if(a(h, l, null != (u = g.srcX) ? u : 0, null != (d = g.srcY) ? d : 0, g.srcWidth, g.srcHeight, null != (f = g.trgX) ? f : 0, null != (p = g.trgY) ? p : 0, g.trgWidth, g.trgHeight), m = c.toDataURL("image/png"), null != o) return o(m, c)
                    })
                }
            }(this), null != o && (l.onerror = o), l.src = t.dataURL
        }, n.prototype.processQueue = function() {
            var t, e, i, n;
            if(e = this.options.parallelUploads, i = this.getUploadingFiles().length, t = i, !(i >= e) && (n = this.getQueuedFiles()).length > 0) {
                if(this.options.uploadMultiple) return this.processFiles(n.slice(0, e - i));
                for(; t < e;) {
                    if(!n.length) return;
                    this.processFile(n.shift()), t++
                }
            }
        }, n.prototype.processFile = function(t) {
            return this.processFiles([t])
        }, n.prototype.processFiles = function(t) {
            var e, i, r;
            for(i = 0, r = t.length; i < r; i++)(e = t[i]).processing = !0, e.status = n.UPLOADING, this.emit("processing", e);
            return this.options.uploadMultiple && this.emit("processingmultiple", t), this.uploadFiles(t)
        }, n.prototype._getFilesWithXhr = function(t) {
            var e;
            return function() {
                var i, n, r, o;
                for(o = [], i = 0, n = (r = this.files).length; i < n; i++)(e = r[i]).xhr === t && o.push(e);
                return o
            }.call(this)
        }, n.prototype.cancelUpload = function(t) {
            var e, i, r, o, a, s, l;
            if(t.status === n.UPLOADING) {
                for(r = 0, a = (i = this._getFilesWithXhr(t.xhr)).length; r < a; r++)(e = i[r]).status = n.CANCELED;
                for(t.xhr.abort(), o = 0, s = i.length; o < s; o++) e = i[o], this.emit("canceled", e);
                this.options.uploadMultiple && this.emit("canceledmultiple", i)
            } else(l = t.status) !== n.ADDED && l !== n.QUEUED || (t.status = n.CANCELED, this.emit("canceled", t), this.options.uploadMultiple && this.emit("canceledmultiple", [t]));
            if(this.options.autoProcessQueue) return this.processQueue()
        }, o = function() {
            var t, e;
            return e = arguments[0], t = 2 <= arguments.length ? c.call(arguments, 1) : [], "function" == typeof e ? e.apply(this, t) : e
        }, n.prototype.uploadFile = function(t) {
            return this.uploadFiles([t])
        }, n.prototype.uploadFiles = function(t) {
            var e, i, a, s, l, c, h, u, d, f, p, g, m, v, y, b, x, _, w, S, C, k, T, A, D, M, E, P, L, I, O, R, N, F, z, H;
            for(H = new XMLHttpRequest, m = 0, x = t.length; m < x; m++)(a = t[m]).xhr = H;
            k = o(this.options.method, t), F = o(this.options.url, t), H.open(k, F, !0), H.timeout = o(this.options.timeout, t), H.withCredentials = !!this.options.withCredentials, O = null, l = function(e) {
                return function() {
                    var i, n, r;
                    for(r = [], i = 0, n = t.length; i < n; i++) a = t[i], r.push(e._errorProcessing(t, O || e.options.dictResponseError.replace("{{statusCode}}", H.status), H));
                    return r
                }
            }(this), N = function(e) {
                return function(i) {
                    var n, r, o, s, l, c, h, u, d;
                    if(null != i)
                        for(u = 100 * i.loaded / i.total, r = 0, s = t.length; r < s; r++)(a = t[r]).upload.progress = u, a.upload.total = i.total, a.upload.bytesSent = i.loaded;
                    else {
                        for(n = !0, u = 100, o = 0, l = t.length; o < l; o++) 100 === (a = t[o]).upload.progress && a.upload.bytesSent === a.upload.total || (n = !1), a.upload.progress = u, a.upload.bytesSent = a.upload.total;
                        if(n) return
                    }
                    for(d = [], h = 0, c = t.length; h < c; h++) a = t[h], d.push(e.emit("uploadprogress", a, u, a.upload.bytesSent));
                    return d
                }
            }(this), H.onload = function(e) {
                return function(i) {
                    var r;
                    if(t[0].status !== n.CANCELED && 4 === H.readyState) {
                        if("arraybuffer" !== H.responseType && "blob" !== H.responseType && (O = H.responseText, H.getResponseHeader("content-type") && ~H.getResponseHeader("content-type").indexOf("application/json"))) try {
                            O = JSON.parse(O)
                        } catch (t) {
                            i = t, O = "Invalid JSON response from server."
                        }
                        return N(), 200 <= (r = H.status) && r < 300 ? e._finished(t, O, i) : l()
                    }
                }
            }(this), H.onerror = function() {
                if(t[0].status !== n.CANCELED) return l()
            }, (null != (D = H.upload) ? D : H).onprogress = N, u = {
                Accept: "application/json",
                "Cache-Control": "no-cache",
                "X-Requested-With": "XMLHttpRequest"
            }, this.options.headers && r(u, this.options.headers);
            for(c in u)(h = u[c]) && H.setRequestHeader(c, h);
            if(s = new FormData, this.options.params) {
                M = this.options.params;
                for(y in M) z = M[y], s.append(y, z)
            }
            for(v = 0, _ = t.length; v < _; v++) a = t[v], this.emit("sending", a, H, s);
            if(this.options.uploadMultiple && this.emit("sendingmultiple", t, H, s), "FORM" === this.element.tagName)
                for(b = 0, w = (E = this.element.querySelectorAll("input, textarea, select, button")).length; b < w; b++)
                    if(f = E[b], p = f.getAttribute("name"), g = f.getAttribute("type"), "SELECT" === f.tagName && f.hasAttribute("multiple"))
                        for(C = 0, S = (P = f.options).length; C < S; C++)(A = P[C]).selected && s.append(p, A.value);
                    else(!g || "checkbox" !== (L = g.toLowerCase()) && "radio" !== L || f.checked) && s.append(p, f.value);
            for(e = 0, R = [], d = T = 0, I = t.length - 1; 0 <= I ? T <= I : T >= I; d = 0 <= I ? ++T : --T) i = function(i) {
                return function(n, r, o) {
                    return function(n) {
                        if(s.append(r, n, o), ++e === t.length) return i.submitRequest(H, s, t)
                    }
                }
            }(this), R.push(this.options.transformFile.call(this, t[d], i(t[d], this._getParamName(d), t[d].upload.filename)));
            return R
        }, n.prototype.submitRequest = function(t, e, i) {
            return t.send(e)
        }, n.prototype._finished = function(t, e, i) {
            var r, o, a;
            for(o = 0, a = t.length; o < a; o++)(r = t[o]).status = n.SUCCESS, this.emit("success", r, e, i), this.emit("complete", r);
            if(this.options.uploadMultiple && (this.emit("successmultiple", t, e, i), this.emit("completemultiple", t)), this.options.autoProcessQueue) return this.processQueue()
        }, n.prototype._errorProcessing = function(t, e, i) {
            var r, o, a;
            for(o = 0, a = t.length; o < a; o++)(r = t[o]).status = n.ERROR, this.emit("error", r, e, i), this.emit("complete", r);
            if(this.options.uploadMultiple && (this.emit("errormultiple", t, e, i), this.emit("completemultiple", t)), this.options.autoProcessQueue) return this.processQueue()
        }, n
    }()).version = "5.1.1", t.options = {}, t.optionsForElement = function(e) {
        return e.getAttribute("id") ? t.options[n(e.getAttribute("id"))] : void 0
    }, t.instances = [], t.forElement = function(t) {
        if("string" == typeof t && (t = document.querySelector(t)), null == (null != t ? t.dropzone : void 0)) throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
        return t.dropzone
    }, t.autoDiscover = !0, t.discover = function() {
        var e, i, n, r, o, a;
        for(document.querySelectorAll ? n = document.querySelectorAll(".dropzone") : (n = [], (e = function(t) {
                var e, i, r, o;
                for(o = [], i = 0, r = t.length; i < r; i++) /(^| )dropzone($| )/.test((e = t[i]).className) ? o.push(n.push(e)) : o.push(void 0);
                return o
            })(document.getElementsByTagName("div")), e(document.getElementsByTagName("form"))), a = [], r = 0, o = n.length; r < o; r++) i = n[r], !1 !== t.optionsForElement(i) ? a.push(new t(i)) : a.push(void 0);
        return a
    }, t.blacklistedBrowsers = [/opera.*Macintosh.*version\/12/i], t.isBrowserSupported = function() {
        var e, i, n, r;
        if(e = !0, window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector)
            if("classList" in document.createElement("a"))
                for(i = 0, n = (r = t.blacklistedBrowsers).length; i < n; i++) r[i].test(navigator.userAgent) && (e = !1);
            else e = !1;
        else e = !1;
        return e
    }, t.dataURItoBlob = function(t) {
        var e, i, n, r, o, a, s;
        for(i = atob(t.split(",")[1]), a = t.split(",")[0].split(":")[1].split(";")[0], e = new ArrayBuffer(i.length), r = new Uint8Array(e), n = o = 0, s = i.length; 0 <= s ? o <= s : o >= s; n = 0 <= s ? ++o : --o) r[n] = i.charCodeAt(n);
        return new Blob([e], {
            type: a
        })
    }, l = function(t, e) {
        var i, n, r, o;
        for(o = [], n = 0, r = t.length; n < r; n++)(i = t[n]) !== e && o.push(i);
        return o
    }, n = function(t) {
        return t.replace(/[\-_](\w)/g, function(t) {
            return t.charAt(1).toUpperCase()
        })
    }, t.createElement = function(t) {
        var e;
        return e = document.createElement("div"), e.innerHTML = t, e.childNodes[0]
    }, t.elementInside = function(t, e) {
        if(t === e) return !0;
        for(; t = t.parentNode;)
            if(t === e) return !0;
        return !1
    }, t.getElement = function(t, e) {
        var i;
        if("string" == typeof t ? i = document.querySelector(t) : null != t.nodeType && (i = t), null == i) throw new Error("Invalid `" + e + "` option provided. Please provide a CSS selector or a plain HTML element.");
        return i
    }, t.getElements = function(t, e) {
        var i, n, r, o, a, s, l;
        if(t instanceof Array) {
            n = [];
            try {
                for(r = 0, a = t.length; r < a; r++) i = t[r], n.push(this.getElement(i, e))
            } catch (t) {
                t,
                n = null
            }
        } else if("string" == typeof t)
            for(n = [], o = 0, s = (l = document.querySelectorAll(t)).length; o < s; o++) i = l[o], n.push(i);
        else null != t.nodeType && (n = [t]);
        if(null == n || !n.length) throw new Error("Invalid `" + e + "` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");
        return n
    }, t.confirm = function(t, e, i) {
        return window.confirm(t) ? e() : null != i ? i() : void 0
    }, t.isValidFile = function(t, e) {
        var i, n, r, o, a;
        if(!e) return !0;
        for(e = e.split(","), i = (o = t.type).replace(/\/.*$/, ""), n = 0, r = e.length; n < r; n++)
            if(a = e[n], "." === (a = a.trim()).charAt(0)) {
                if(-1 !== t.name.toLowerCase().indexOf(a.toLowerCase(), t.name.length - a.length)) return !0
            } else if(/\/\*$/.test(a)) {
            if(i === a.replace(/\/.*$/, "")) return !0
        } else if(o === a) return !0;
        return !1
    }, "undefined" != typeof jQuery && null !== jQuery && (jQuery.fn.dropzone = function(e) {
        return this.each(function() {
            return new t(this, e)
        })
    }), "undefined" != typeof module && null !== module ? module.exports = t : window.Dropzone = t, t.ADDED = "added", t.QUEUED = "queued", t.ACCEPTED = t.QUEUED, t.UPLOADING = "uploading", t.PROCESSING = t.UPLOADING, t.CANCELED = "canceled", t.ERROR = "error", t.SUCCESS = "success", o = function(t) {
        var e, i, n, r, o, a, s, l;
        for(t.naturalWidth, o = t.naturalHeight, (e = document.createElement("canvas")).width = 1, e.height = o, (i = e.getContext("2d")).drawImage(t, 0, 0), n = i.getImageData(1, 0, 1, o).data, l = 0, r = o, a = o; a > l;) 0 === n[4 * (a - 1) + 3] ? r = a : l = a, a = r + l >> 1;
        return 0 === (s = a / o) ? 1 : s
    }, a = function(t, e, i, n, r, a, s, l, c, h) {
        var u;
        return u = o(e), t.drawImage(e, i, n, r, a, s, l, c, h / u)
    }, i = function() {
        function t() {}
        return t.KEY_STR = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", t.encode64 = function(t) {
            var e, i, n, r, o, a, s, l, c;
            for(c = "", e = void 0, i = void 0, n = "", r = void 0, o = void 0, a = void 0, s = "", l = 0;;)
                if(e = t[l++], i = t[l++], n = t[l++], r = e >> 2, o = (3 & e) << 4 | i >> 4, a = (15 & i) << 2 | n >> 6, s = 63 & n, isNaN(i) ? a = s = 64 : isNaN(n) && (s = 64), c = c + this.KEY_STR.charAt(r) + this.KEY_STR.charAt(o) + this.KEY_STR.charAt(a) + this.KEY_STR.charAt(s), e = i = n = "", r = o = a = s = "", !(l < t.length)) break;
            return c
        }, t.restore = function(t, e) {
            var i, n, r;
            return t.match("data:image/jpeg;base64,") ? (n = this.decode64(t.replace("data:image/jpeg;base64,", "")), r = this.slice2Segments(n), i = this.exifManipulation(e, r), "data:image/jpeg;base64," + this.encode64(i)) : e
        }, t.exifManipulation = function(t, e) {
            var i, n;
            return i = this.getExifArray(e), n = this.insertExif(t, i), new Uint8Array(n)
        }, t.getExifArray = function(t) {
            var e, i;
            for(e = void 0, i = 0; i < t.length;) {
                if(255 === (e = t[i])[0] & 225 === e[1]) return e;
                i++
            }
            return []
        }, t.insertExif = function(t, e) {
            var i, n, r, o, a, s;
            return o = t.replace("data:image/jpeg;base64,", ""), r = this.decode64(o), s = r.indexOf(255, 3), a = r.slice(0, s), n = r.slice(s), i = a, i = i.concat(e), i = i.concat(n)
        }, t.slice2Segments = function(t) {
            var e, i, n, r;
            for(i = 0, r = [];;) {
                if(255 === t[i] & 218 === t[i + 1]) break;
                if(255 === t[i] & 216 === t[i + 1] ? i += 2 : (e = i + (256 * t[i + 2] + t[i + 3]) + 2, n = t.slice(i, e), r.push(n), i = e), i > t.length) break
            }
            return r
        }, t.decode64 = function(t) {
            var e, i, n, r, o, a, s, l, c;
            for("", i = void 0, n = void 0, r = "", o = void 0, a = void 0, s = void 0, l = "", c = 0, e = [], /[^A-Za-z0-9\+\/\=]/g.exec(t) && console.warning("There were invalid base64 characters in the input text.\nValid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\nExpect errors in decoding."), t = t.replace(/[^A-Za-z0-9\+\/\=]/g, "");;)
                if(o = this.KEY_STR.indexOf(t.charAt(c++)), a = this.KEY_STR.indexOf(t.charAt(c++)), s = this.KEY_STR.indexOf(t.charAt(c++)), l = this.KEY_STR.indexOf(t.charAt(c++)), i = o << 2 | a >> 4, n = (15 & a) << 4 | s >> 2, r = (3 & s) << 6 | l, e.push(i), 64 !== s && e.push(n), 64 !== l && e.push(r), i = n = r = "", o = a = s = l = "", !(c < t.length)) break;
            return e
        }, t
    }(), r = function(t, e) {
        var i, n, r, o, a, s, l, c, h;
        if(r = !1, h = !0, n = t.document, c = n.documentElement, i = n.addEventListener ? "addEventListener" : "attachEvent", l = n.addEventListener ? "removeEventListener" : "detachEvent", s = n.addEventListener ? "" : "on", o = function(i) {
                if("readystatechange" !== i.type || "complete" === n.readyState) return ("load" === i.type ? t : n)[l](s + i.type, o, !1), !r && (r = !0) ? e.call(t, i.type || i) : void 0
            }, a = function() {
                try {
                    c.doScroll("left")
                } catch (t) {
                    return t, void setTimeout(a, 50)
                }
                return o("poll")
            }, "complete" !== n.readyState) {
            if(n.createEventObject && c.doScroll) {
                try {
                    h = !t.frameElement
                } catch (t) {}
                h && a()
            }
            return n[i](s + "DOMContentLoaded", o, !1), n[i](s + "readystatechange", o, !1), t[i](s + "load", o, !1)
        }
    }, t._autoDiscoverFunction = function() {
        if(t.autoDiscover) return t.discover()
    }, r(window, t._autoDiscoverFunction)
}.call(this);
var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
return typeof t
} : function(t) {
return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
};
! function(t, e) {
"use strict";
if(!e) throw new Error("dropzone.js required.");
var i = e.prototype.defaultOptions.error;
e.prototype.defaultOptions = t.extend({}, e.prototype.defaultOptions, {
    previewTemplate: '\n<div class="dz-preview dz-file-preview">\n  <div class="dz-details">\n    <div class="dz-filename" data-dz-name></div>\n    <div class="dz-size" data-dz-size></div>\n    <div class="dz-thumbnail">\n      <img data-dz-thumbnail>\n      <span class="dz-nopreview"></span>\n      <div class="dz-success-mark"></div>\n      <div class="dz-error-mark"></div>\n      <div class="dz-error-message"><span data-dz-errormessage></span></div>\n    </div>\n  </div>\n  <div class="progress">\n    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>\n  </div>\n</div>',
    addRemoveLinks: !0,
    error: function(e, n) {
        var r = i.call(this, e, n);
        return e.previewElement && t(e.previewElement).find(".progress-bar-success").removeClass("progress-bar-success").addClass("progress-bar-danger"), r
    }
}), t.fn.dropzone = function(i) {
    for(var n = arguments.length, r = Array(n > 1 ? n - 1 : 0), o = 1; o < n; o++) r[o - 1] = arguments[o];
    var a = void 0,
        s = this.each(function() {
            var n = t(this).data("dropzone"),
                o = "object" === (void 0 === i ? "undefined" : _typeof(i)) ? i : null;
            if(n || (n = new e(this, o), t(this).data("dropzone", n)), "string" == typeof i) {
                var s;
                if(!n[i]) throw new Error('No method named "' + i + '".');
                a = (s = n)[i].apply(s, r)
            }
        });
    return void 0 !== a ? a : s
}
}(jQuery, window.Dropzone),
function(t) {
"object" == typeof exports ? module.exports = t(require("jquery")) : "function" == typeof define && define.amd ? define(["jquery"], t) : t(jQuery)
}(function(t) {
"use strict";
var e = {},
    i = Math.max,
    n = Math.min;
e.c = {}, e.c.d = t(document), e.c.t = function(t) {
    return t.originalEvent.touches.length - 1
}, e.o = function() {
    var i = this;
    this.o = null, this.$ = null, this.i = null, this.g = null, this.v = null, this.cv = null, this.x = 0, this.y = 0, this.w = 0, this.h = 0, this.$c = null, this.c = null, this.t = 0, this.isInit = !1, this.fgColor = null, this.pColor = null, this.dH = null, this.cH = null, this.eH = null, this.rH = null, this.scale = 1, this.relative = !1, this.relativeWidth = !1, this.relativeHeight = !1, this.$div = null, this.run = function() {
        var e = function(t, e) {
            var n;
            for(n in e) i.o[n] = e[n];
            i._carve().init(), i._configure()._draw()
        };
        if(!this.$.data("kontroled")) {
            if(this.$.data("kontroled", !0), this.extend(), this.o = t.extend({
                    min: void 0 !== this.$.data("min") ? this.$.data("min") : 0,
                    max: void 0 !== this.$.data("max") ? this.$.data("max") : 100,
                    stopper: !0,
                    readOnly: this.$.data("readonly") || "readonly" === this.$.attr("readonly"),
                    cursor: !0 === this.$.data("cursor") && 30 || this.$.data("cursor") || 0,
                    thickness: this.$.data("thickness") && Math.max(Math.min(this.$.data("thickness"), 1), .01) || .35,
                    lineCap: this.$.data("linecap") || "butt",
                    width: this.$.data("width") || 200,
                    height: this.$.data("height") || 200,
                    displayInput: null == this.$.data("displayinput") || this.$.data("displayinput"),
                    displayPrevious: this.$.data("displayprevious"),
                    fgColor: this.$.data("fgcolor") || "#87CEEB",
                    inputColor: this.$.data("inputcolor"),
                    font: this.$.data("font") || "Arial",
                    fontWeight: this.$.data("font-weight") || "bold",
                    inline: !1,
                    step: this.$.data("step") || 1,
                    rotation: this.$.data("rotation"),
                    draw: null,
                    change: null,
                    cancel: null,
                    release: null,
                    format: function(t) {
                        return t
                    },
                    parse: function(t) {
                        return parseFloat(t)
                    }
                }, this.o), this.o.flip = "anticlockwise" === this.o.rotation || "acw" === this.o.rotation, this.o.inputColor || (this.o.inputColor = this.o.fgColor), this.$.is("fieldset") ? (this.v = {}, this.i = this.$.find("input"), this.i.each(function(e) {
                    var n = t(this);
                    i.i[e] = n, i.v[e] = i.o.parse(n.val()), n.bind("change blur", function() {
                        var t = {};
                        t[e] = n.val(), i.val(i._validate(t))
                    })
                }), this.$.find("legend").remove()) : (this.i = this.$, this.v = this.o.parse(this.$.val()), "" === this.v && (this.v = this.o.min), this.$.bind("change blur", function() {
                    i.val(i._validate(i.o.parse(i.$.val())))
                })), !this.o.displayInput && this.$.hide(), this.$c = t(document.createElement("canvas")).attr({
                    width: this.o.width,
                    height: this.o.height
                }), this.$div = t('<div style="' + (this.o.inline ? "display:inline;" : "") + "width:" + this.o.width + "px;height:" + this.o.height + 'px;"></div>'), this.$.wrap(this.$div).before(this.$c), this.$div = this.$.parent(), "undefined" != typeof G_vmlCanvasManager && G_vmlCanvasManager.initElement(this.$c[0]), this.c = this.$c[0].getContext ? this.$c[0].getContext("2d") : null, !this.c) throw {
                name: "CanvasNotSupportedException",
                message: "Canvas not supported. Please use excanvas on IE8.0.",
                toString: function() {
                    return this.name + ": " + this.message
                }
            };
            return this.scale = (window.devicePixelRatio || 1) / (this.c.webkitBackingStorePixelRatio || this.c.mozBackingStorePixelRatio || this.c.msBackingStorePixelRatio || this.c.oBackingStorePixelRatio || this.c.backingStorePixelRatio || 1), this.relativeWidth = this.o.width % 1 != 0 && this.o.width.indexOf("%"), this.relativeHeight = this.o.height % 1 != 0 && this.o.height.indexOf("%"), this.relative = this.relativeWidth || this.relativeHeight, this._carve(), this.v instanceof Object ? (this.cv = {}, this.copy(this.v, this.cv)) : this.cv = this.v, this.$.bind("configure", e).parent().bind("configure", e), this._listen()._configure()._xy().init(), this.isInit = !0, this.$.val(this.o.format(this.v)), this._draw(), this
        }
    }, this._carve = function() {
        if(this.relative) {
            var t = this.relativeWidth ? this.$div.parent().width() * parseInt(this.o.width) / 100 : this.$div.parent().width(),
                e = this.relativeHeight ? this.$div.parent().height() * parseInt(this.o.height) / 100 : this.$div.parent().height();
            this.w = this.h = Math.min(t, e)
        } else this.w = this.o.width, this.h = this.o.height;
        return this.$div.css({
            width: this.w + "px",
            height: this.h + "px"
        }), this.$c.attr({
            width: this.w,
            height: this.h
        }), 1 !== this.scale && (this.$c[0].width = this.$c[0].width * this.scale, this.$c[0].height = this.$c[0].height * this.scale, this.$c.width(this.w), this.$c.height(this.h)), this
    }, this._draw = function() {
        var t = !0;
        i.g = i.c, i.clear(), i.dH && (t = i.dH()), !1 !== t && i.draw()
    }, this._touch = function(t) {
        var n = function(t) {
            var e = i.xy2val(t.originalEvent.touches[i.t].pageX, t.originalEvent.touches[i.t].pageY);
            e != i.cv && (i.cH && !1 === i.cH(e) || (i.change(i._validate(e)), i._draw()))
        };
        return this.t = e.c.t(t), n(t), e.c.d.bind("touchmove.k", n).bind("touchend.k", function() {
            e.c.d.unbind("touchmove.k touchend.k"), i.val(i.cv)
        }), this
    }, this._mouse = function(t) {
        var n = function(t) {
            var e = i.xy2val(t.pageX, t.pageY);
            e != i.cv && (i.cH && !1 === i.cH(e) || (i.change(i._validate(e)), i._draw()))
        };
        return n(t), e.c.d.bind("mousemove.k", n).bind("keyup.k", function(t) {
            if(27 === t.keyCode) {
                if(e.c.d.unbind("mouseup.k mousemove.k keyup.k"), i.eH && !1 === i.eH()) return;
                i.cancel()
            }
        }).bind("mouseup.k", function(t) {
            e.c.d.unbind("mousemove.k mouseup.k keyup.k"), i.val(i.cv)
        }), this
    }, this._xy = function() {
        var t = this.$c.offset();
        return this.x = t.left, this.y = t.top, this
    }, this._listen = function() {
        return this.o.readOnly ? this.$.attr("readonly", "readonly") : (this.$c.bind("mousedown", function(t) {
            t.preventDefault(), i._xy()._mouse(t)
        }).bind("touchstart", function(t) {
            t.preventDefault(), i._xy()._touch(t)
        }), this.listen()), this.relative && t(window).resize(function() {
            i._carve().init(), i._draw()
        }), this
    }, this._configure = function() {
        return this.o.draw && (this.dH = this.o.draw), this.o.change && (this.cH = this.o.change), this.o.cancel && (this.eH = this.o.cancel), this.o.release && (this.rH = this.o.release), this.o.displayPrevious ? (this.pColor = this.h2rgba(this.o.fgColor, "0.4"), this.fgColor = this.h2rgba(this.o.fgColor, "0.6")) : this.fgColor = this.o.fgColor, this
    }, this._clear = function() {
        this.$c[0].width = this.$c[0].width
    }, this._validate = function(t) {
        var e = ~~((t < 0 ? -.5 : .5) + t / this.o.step) * this.o.step;
        return Math.round(100 * e) / 100
    }, this.listen = function() {}, this.extend = function() {}, this.init = function() {}, this.change = function(t) {}, this.val = function(t) {}, this.xy2val = function(t, e) {}, this.draw = function() {}, this.clear = function() {
        this._clear()
    }, this.h2rgba = function(t, e) {
        var i;
        return t = t.substring(1, 7), "rgba(" + (i = [parseInt(t.substring(0, 2), 16), parseInt(t.substring(2, 4), 16), parseInt(t.substring(4, 6), 16)])[0] + "," + i[1] + "," + i[2] + "," + e + ")"
    }, this.copy = function(t, e) {
        for(var i in t) e[i] = t[i]
    }
}, e.Dial = function() {
    e.o.call(this), this.startAngle = null, this.xy = null, this.radius = null, this.lineWidth = null, this.cursorExt = null, this.w2 = null, this.PI2 = 2 * Math.PI, this.extend = function() {
        this.o = t.extend({
            bgColor: this.$.data("bgcolor") || "#EEEEEE",
            angleOffset: this.$.data("angleoffset") || 0,
            angleArc: this.$.data("anglearc") || 360,
            inline: !0
        }, this.o)
    }, this.val = function(t, e) {
        if(null == t) return this.v;
        t = this.o.parse(t), !1 !== e && t != this.v && this.rH && !1 === this.rH(t) || (this.cv = this.o.stopper ? i(n(t, this.o.max), this.o.min) : t, this.v = this.cv, this.$.val(this.o.format(this.v)), this._draw())
    }, this.xy2val = function(t, e) {
        var r, o;
        return r = Math.atan2(t - (this.x + this.w2), -(e - this.y - this.w2)) - this.angleOffset, this.o.flip && (r = this.angleArc - r - this.PI2), this.angleArc != this.PI2 && r < 0 && r > -.5 ? r = 0 : r < 0 && (r += this.PI2), o = r * (this.o.max - this.o.min) / this.angleArc + this.o.min, this.o.stopper && (o = i(n(o, this.o.max), this.o.min)), o
    }, this.listen = function() {
        var e, r, o, a, s = this,
            l = function(t) {
                t.preventDefault();
                var o = t.originalEvent,
                    a = o.detail || o.wheelDeltaX,
                    l = o.detail || o.wheelDeltaY,
                    c = s._validate(s.o.parse(s.$.val())) + (a > 0 || l > 0 ? s.o.step : a < 0 || l < 0 ? -s.o.step : 0);
                c = i(n(c, s.o.max), s.o.min), s.val(c, !1), s.rH && (clearTimeout(e), e = setTimeout(function() {
                    s.rH(c), e = null
                }, 100), r || (r = setTimeout(function() {
                    e && s.rH(c), r = null
                }, 200)))
            },
            c = 1,
            h = {
                37: -s.o.step,
                38: s.o.step,
                39: s.o.step,
                40: -s.o.step
            };
        this.$.bind("keydown", function(e) {
            var r = e.keyCode;
            if(r >= 96 && r <= 105 && (r = e.keyCode = r - 48), o = parseInt(String.fromCharCode(r)), isNaN(o) && (13 !== r && 8 !== r && 9 !== r && 189 !== r && (190 !== r || s.$.val().match(/\./)) && e.preventDefault(), t.inArray(r, [37, 38, 39, 40]) > -1)) {
                e.preventDefault();
                var l = s.o.parse(s.$.val()) + h[r] * c;
                s.o.stopper && (l = i(n(l, s.o.max), s.o.min)), s.change(s._validate(l)), s._draw(), a = window.setTimeout(function() {
                    c *= 2
                }, 30)
            }
        }).bind("keyup", function(t) {
            isNaN(o) ? a && (window.clearTimeout(a), a = null, c = 1, s.val(s.$.val())) : s.$.val() > s.o.max && s.$.val(s.o.max) || s.$.val() < s.o.min && s.$.val(s.o.min)
        }), this.$c.bind("mousewheel DOMMouseScroll", l), this.$.bind("mousewheel DOMMouseScroll", l)
    }, this.init = function() {
        (this.v < this.o.min || this.v > this.o.max) && (this.v = this.o.min), this.$.val(this.v), this.w2 = this.w / 2, this.cursorExt = this.o.cursor / 100, this.xy = this.w2 * this.scale, this.lineWidth = this.xy * this.o.thickness, this.lineCap = this.o.lineCap, this.radius = this.xy - this.lineWidth / 2, this.o.angleOffset && (this.o.angleOffset = isNaN(this.o.angleOffset) ? 0 : this.o.angleOffset), this.o.angleArc && (this.o.angleArc = isNaN(this.o.angleArc) ? this.PI2 : this.o.angleArc), this.angleOffset = this.o.angleOffset * Math.PI / 180, this.angleArc = this.o.angleArc * Math.PI / 180, this.startAngle = 1.5 * Math.PI + this.angleOffset, this.endAngle = 1.5 * Math.PI + this.angleOffset + this.angleArc;
        var t = i(String(Math.abs(this.o.max)).length, String(Math.abs(this.o.min)).length, 2) + 2;
        this.o.displayInput && this.i.css({
            width: (this.w / 2 + 4 >> 0) + "px",
            height: (this.w / 3 >> 0) + "px",
            position: "absolute",
            "vertical-align": "middle",
            "margin-top": (this.w / 3 >> 0) + "px",
            "margin-left": "-" + (3 * this.w / 4 + 2 >> 0) + "px",
            border: 0,
            background: "none",
            font: this.o.fontWeight + " " + (this.w / t >> 0) + "px " + this.o.font,
            "text-align": "center",
            color: this.o.inputColor || this.o.fgColor,
            padding: "0px",
            "-webkit-appearance": "none"
        }) || this.i.css({
            width: "0px",
            visibility: "hidden"
        })
    }, this.change = function(t) {
        this.cv = t, this.$.val(this.o.format(t))
    }, this.angle = function(t) {
        return (t - this.o.min) * this.angleArc / (this.o.max - this.o.min)
    }, this.arc = function(t) {
        var e, i;
        return t = this.angle(t), i = this.o.flip ? (e = this.endAngle + 1e-5) - t - 1e-5 : (e = this.startAngle - 1e-5) + t + 1e-5, this.o.cursor && (e = i - this.cursorExt) && (i += this.cursorExt), {
            s: e,
            e: i,
            d: this.o.flip && !this.o.cursor
        }
    }, this.draw = function() {
        var t, e = this.g,
            i = this.arc(this.cv),
            n = 1;
        e.lineWidth = this.lineWidth, e.lineCap = this.lineCap, "none" !== this.o.bgColor && (e.beginPath(), e.strokeStyle = this.o.bgColor, e.arc(this.xy, this.xy, this.radius, this.endAngle - 1e-5, this.startAngle + 1e-5, !0), e.stroke()), this.o.displayPrevious && (t = this.arc(this.v), e.beginPath(), e.strokeStyle = this.pColor, e.arc(this.xy, this.xy, this.radius, t.s, t.e, t.d), e.stroke(), n = this.cv == this.v), e.beginPath(), e.strokeStyle = n ? this.o.fgColor : this.fgColor, e.arc(this.xy, this.xy, this.radius, i.s, i.e, i.d), e.stroke()
    }, this.cancel = function() {
        this.val(this.v)
    }
}, t.fn.dial = t.fn.knob = function(i) {
    return this.each(function() {
        var n = new e.Dial;
        n.o = i, n.$ = t(this), n.run()
    }).parent()
}
});