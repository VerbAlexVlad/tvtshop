/*!
 * Bootstrap v3.4.1 (https://getbootstrap.com/)
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under the MIT license
 */
if(typeof jQuery==='undefined'){throw new Error('Bootstrap\'s JavaScript requires jQuery')}
+function($){'use strict';var version=$.fn.jquery.split(' ')[0].split('.')
if((version[0]<2&&version[1]<9)||(version[0]==1&&version[1]==9&&version[2]<1)||(version[0]>3)){throw new Error('Bootstrap\'s JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4')}}(jQuery);+function($){'use strict';function transitionEnd(){var el=document.createElement('bootstrap')
var transEndEventNames={WebkitTransition:'webkitTransitionEnd',MozTransition:'transitionend',OTransition:'oTransitionEnd otransitionend',transition:'transitionend'}
for(var name in transEndEventNames){if(el.style[name]!==undefined){return{end:transEndEventNames[name]}}}
return false}
$.fn.emulateTransitionEnd=function(duration){var called=false
var $el=this
$(this).one('bsTransitionEnd',function(){called=true})
var callback=function(){if(!called)$($el).trigger($.support.transition.end)}
setTimeout(callback,duration)
return this}
$(function(){$.support.transition=transitionEnd()
if(!$.support.transition)return
$.event.special.bsTransitionEnd={bindType:$.support.transition.end,delegateType:$.support.transition.end,handle:function(e){if($(e.target).is(this))return e.handleObj.handler.apply(this,arguments)}}})}(jQuery);+function($){'use strict';var dismiss='[data-dismiss="alert"]'
var Alert=function(el){$(el).on('click',dismiss,this.close)}
Alert.VERSION='3.4.1'
Alert.TRANSITION_DURATION=150
Alert.prototype.close=function(e){var $this=$(this)
var selector=$this.attr('data-target')
if(!selector){selector=$this.attr('href')
selector=selector&&selector.replace(/.*(?=#[^\s]*$)/,'')}
selector=selector==='#'?[]:selector
var $parent=$(document).find(selector)
if(e)e.preventDefault()
if(!$parent.length){$parent=$this.closest('.alert')}
$parent.trigger(e=$.Event('close.bs.alert'))
if(e.isDefaultPrevented())return
$parent.removeClass('in')
function removeElement(){$parent.detach().trigger('closed.bs.alert').remove()}
$.support.transition&&$parent.hasClass('fade')?$parent.one('bsTransitionEnd',removeElement).emulateTransitionEnd(Alert.TRANSITION_DURATION):removeElement()}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.alert')
if(!data)$this.data('bs.alert',(data=new Alert(this)))
if(typeof option=='string')data[option].call($this)})}
var old=$.fn.alert
$.fn.alert=Plugin
$.fn.alert.Constructor=Alert
$.fn.alert.noConflict=function(){$.fn.alert=old
return this}
$(document).on('click.bs.alert.data-api',dismiss,Alert.prototype.close)}(jQuery);+function($){'use strict';var Button=function(element,options){this.$element=$(element)
this.options=$.extend({},Button.DEFAULTS,options)
this.isLoading=false}
Button.VERSION='3.4.1'
Button.DEFAULTS={loadingText:'loading...'}
Button.prototype.setState=function(state){var d='disabled'
var $el=this.$element
var val=$el.is('input')?'val':'html'
var data=$el.data()
state+='Text'
if(data.resetText==null)$el.data('resetText',$el[val]())
setTimeout($.proxy(function(){$el[val](data[state]==null?this.options[state]:data[state])
if(state=='loadingText'){this.isLoading=true
$el.addClass(d).attr(d,d).prop(d,true)}else if(this.isLoading){this.isLoading=false
$el.removeClass(d).removeAttr(d).prop(d,false)}},this),0)}
Button.prototype.toggle=function(){var changed=true
var $parent=this.$element.closest('[data-toggle="buttons"]')
if($parent.length){var $input=this.$element.find('input')
if($input.prop('type')=='radio'){if($input.prop('checked'))changed=false
$parent.find('.active').removeClass('active')
this.$element.addClass('active')}else if($input.prop('type')=='checkbox'){if(($input.prop('checked'))!==this.$element.hasClass('active'))changed=false
this.$element.toggleClass('active')}
$input.prop('checked',this.$element.hasClass('active'))
if(changed)$input.trigger('change')}else{this.$element.attr('aria-pressed',!this.$element.hasClass('active'))
this.$element.toggleClass('active')}}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.button')
var options=typeof option=='object'&&option
if(!data)$this.data('bs.button',(data=new Button(this,options)))
if(option=='toggle')data.toggle()
else if(option)data.setState(option)})}
var old=$.fn.button
$.fn.button=Plugin
$.fn.button.Constructor=Button
$.fn.button.noConflict=function(){$.fn.button=old
return this}
$(document).on('click.bs.button.data-api','[data-toggle^="button"]',function(e){var $btn=$(e.target).closest('.btn')
Plugin.call($btn,'toggle')
if(!($(e.target).is('input[type="radio"], input[type="checkbox"]'))){e.preventDefault()
if($btn.is('input,button'))$btn.trigger('focus')
else $btn.find('input:visible,button:visible').first().trigger('focus')}}).on('focus.bs.button.data-api blur.bs.button.data-api','[data-toggle^="button"]',function(e){$(e.target).closest('.btn').toggleClass('focus',/^focus(in)?$/.test(e.type))})}(jQuery);+function($){'use strict';var Carousel=function(element,options){this.$element=$(element)
this.$indicators=this.$element.find('.carousel-indicators')
this.options=options
this.paused=null
this.sliding=null
this.interval=null
this.$active=null
this.$items=null
this.options.keyboard&&this.$element.on('keydown.bs.carousel',$.proxy(this.keydown,this))
this.options.pause=='hover'&&!('ontouchstart'in document.documentElement)&&this.$element.on('mouseenter.bs.carousel',$.proxy(this.pause,this)).on('mouseleave.bs.carousel',$.proxy(this.cycle,this))}
Carousel.VERSION='3.4.1'
Carousel.TRANSITION_DURATION=600
Carousel.DEFAULTS={interval:5000,pause:'hover',wrap:true,keyboard:true}
Carousel.prototype.keydown=function(e){if(/input|textarea/i.test(e.target.tagName))return
switch(e.which){case 37:this.prev();break
case 39:this.next();break
default:return}
e.preventDefault()}
Carousel.prototype.cycle=function(e){e||(this.paused=false)
this.interval&&clearInterval(this.interval)
this.options.interval&&!this.paused&&(this.interval=setInterval($.proxy(this.next,this),this.options.interval))
return this}
Carousel.prototype.getItemIndex=function(item){this.$items=item.parent().children('.item')
return this.$items.index(item||this.$active)}
Carousel.prototype.getItemForDirection=function(direction,active){var activeIndex=this.getItemIndex(active)
var willWrap=(direction=='prev'&&activeIndex===0)||(direction=='next'&&activeIndex==(this.$items.length-1))
if(willWrap&&!this.options.wrap)return active
var delta=direction=='prev'?-1:1
var itemIndex=(activeIndex+delta)%this.$items.length
return this.$items.eq(itemIndex)}
Carousel.prototype.to=function(pos){var that=this
var activeIndex=this.getItemIndex(this.$active=this.$element.find('.item.active'))
if(pos>(this.$items.length-1)||pos<0)return
if(this.sliding)return this.$element.one('slid.bs.carousel',function(){that.to(pos)})
if(activeIndex==pos)return this.pause().cycle()
return this.slide(pos>activeIndex?'next':'prev',this.$items.eq(pos))}
Carousel.prototype.pause=function(e){e||(this.paused=true)
if(this.$element.find('.next, .prev').length&&$.support.transition){this.$element.trigger($.support.transition.end)
this.cycle(true)}
this.interval=clearInterval(this.interval)
return this}
Carousel.prototype.next=function(){if(this.sliding)return
return this.slide('next')}
Carousel.prototype.prev=function(){if(this.sliding)return
return this.slide('prev')}
Carousel.prototype.slide=function(type,next){var $active=this.$element.find('.item.active')
var $next=next||this.getItemForDirection(type,$active)
var isCycling=this.interval
var direction=type=='next'?'left':'right'
var that=this
if($next.hasClass('active'))return(this.sliding=false)
var relatedTarget=$next[0]
var slideEvent=$.Event('slide.bs.carousel',{relatedTarget:relatedTarget,direction:direction})
this.$element.trigger(slideEvent)
if(slideEvent.isDefaultPrevented())return
this.sliding=true
isCycling&&this.pause()
if(this.$indicators.length){this.$indicators.find('.active').removeClass('active')
var $nextIndicator=$(this.$indicators.children()[this.getItemIndex($next)])
$nextIndicator&&$nextIndicator.addClass('active')}
var slidEvent=$.Event('slid.bs.carousel',{relatedTarget:relatedTarget,direction:direction})
if($.support.transition&&this.$element.hasClass('slide')){$next.addClass(type)
if(typeof $next==='object'&&$next.length){$next[0].offsetWidth}
$active.addClass(direction)
$next.addClass(direction)
$active.one('bsTransitionEnd',function(){$next.removeClass([type,direction].join(' ')).addClass('active')
$active.removeClass(['active',direction].join(' '))
that.sliding=false
setTimeout(function(){that.$element.trigger(slidEvent)},0)}).emulateTransitionEnd(Carousel.TRANSITION_DURATION)}else{$active.removeClass('active')
$next.addClass('active')
this.sliding=false
this.$element.trigger(slidEvent)}
isCycling&&this.cycle()
return this}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.carousel')
var options=$.extend({},Carousel.DEFAULTS,$this.data(),typeof option=='object'&&option)
var action=typeof option=='string'?option:options.slide
if(!data)$this.data('bs.carousel',(data=new Carousel(this,options)))
if(typeof option=='number')data.to(option)
else if(action)data[action]()
else if(options.interval)data.pause().cycle()})}
var old=$.fn.carousel
$.fn.carousel=Plugin
$.fn.carousel.Constructor=Carousel
$.fn.carousel.noConflict=function(){$.fn.carousel=old
return this}
var clickHandler=function(e){var $this=$(this)
var href=$this.attr('href')
if(href){href=href.replace(/.*(?=#[^\s]+$)/,'')}
var target=$this.attr('data-target')||href
var $target=$(document).find(target)
if(!$target.hasClass('carousel'))return
var options=$.extend({},$target.data(),$this.data())
var slideIndex=$this.attr('data-slide-to')
if(slideIndex)options.interval=false
Plugin.call($target,options)
if(slideIndex){$target.data('bs.carousel').to(slideIndex)}
e.preventDefault()}
$(document).on('click.bs.carousel.data-api','[data-slide]',clickHandler).on('click.bs.carousel.data-api','[data-slide-to]',clickHandler)
$(window).on('load',function(){$('[data-ride="carousel"]').each(function(){var $carousel=$(this)
Plugin.call($carousel,$carousel.data())})})}(jQuery);+function($){'use strict';var Collapse=function(element,options){this.$element=$(element)
this.options=$.extend({},Collapse.DEFAULTS,options)
this.$trigger=$('[data-toggle="collapse"][href="#'+element.id+'"],'+'[data-toggle="collapse"][data-target="#'+element.id+'"]')
this.transitioning=null
if(this.options.parent){this.$parent=this.getParent()}else{this.addAriaAndCollapsedClass(this.$element,this.$trigger)}
if(this.options.toggle)this.toggle()}
Collapse.VERSION='3.4.1'
Collapse.TRANSITION_DURATION=350
Collapse.DEFAULTS={toggle:true}
Collapse.prototype.dimension=function(){var hasWidth=this.$element.hasClass('width')
return hasWidth?'width':'height'}
Collapse.prototype.show=function(){if(this.transitioning||this.$element.hasClass('in'))return
var activesData
var actives=this.$parent&&this.$parent.children('.panel').children('.in, .collapsing')
if(actives&&actives.length){activesData=actives.data('bs.collapse')
if(activesData&&activesData.transitioning)return}
var startEvent=$.Event('show.bs.collapse')
this.$element.trigger(startEvent)
if(startEvent.isDefaultPrevented())return
if(actives&&actives.length){Plugin.call(actives,'hide')
activesData||actives.data('bs.collapse',null)}
var dimension=this.dimension()
this.$element.removeClass('collapse').addClass('collapsing')[dimension](0).attr('aria-expanded',true)
this.$trigger.removeClass('collapsed').attr('aria-expanded',true)
this.transitioning=1
var complete=function(){this.$element.removeClass('collapsing').addClass('collapse in')[dimension]('')
this.transitioning=0
this.$element.trigger('shown.bs.collapse')}
if(!$.support.transition)return complete.call(this)
var scrollSize=$.camelCase(['scroll',dimension].join('-'))
this.$element.one('bsTransitionEnd',$.proxy(complete,this)).emulateTransitionEnd(Collapse.TRANSITION_DURATION)[dimension](this.$element[0][scrollSize])}
Collapse.prototype.hide=function(){if(this.transitioning||!this.$element.hasClass('in'))return
var startEvent=$.Event('hide.bs.collapse')
this.$element.trigger(startEvent)
if(startEvent.isDefaultPrevented())return
var dimension=this.dimension()
this.$element[dimension](this.$element[dimension]())[0].offsetHeight
this.$element.addClass('collapsing').removeClass('collapse in').attr('aria-expanded',false)
this.$trigger.addClass('collapsed').attr('aria-expanded',false)
this.transitioning=1
var complete=function(){this.transitioning=0
this.$element.removeClass('collapsing').addClass('collapse').trigger('hidden.bs.collapse')}
if(!$.support.transition)return complete.call(this)
this.$element
[dimension](0).one('bsTransitionEnd',$.proxy(complete,this)).emulateTransitionEnd(Collapse.TRANSITION_DURATION)}
Collapse.prototype.toggle=function(){this[this.$element.hasClass('in')?'hide':'show']()}
Collapse.prototype.getParent=function(){return $(document).find(this.options.parent).find('[data-toggle="collapse"][data-parent="'+this.options.parent+'"]').each($.proxy(function(i,element){var $element=$(element)
this.addAriaAndCollapsedClass(getTargetFromTrigger($element),$element)},this)).end()}
Collapse.prototype.addAriaAndCollapsedClass=function($element,$trigger){var isOpen=$element.hasClass('in')
$element.attr('aria-expanded',isOpen)
$trigger.toggleClass('collapsed',!isOpen).attr('aria-expanded',isOpen)}
function getTargetFromTrigger($trigger){var href
var target=$trigger.attr('data-target')||(href=$trigger.attr('href'))&&href.replace(/.*(?=#[^\s]+$)/,'')
return $(document).find(target)}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.collapse')
var options=$.extend({},Collapse.DEFAULTS,$this.data(),typeof option=='object'&&option)
if(!data&&options.toggle&&/show|hide/.test(option))options.toggle=false
if(!data)$this.data('bs.collapse',(data=new Collapse(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.collapse
$.fn.collapse=Plugin
$.fn.collapse.Constructor=Collapse
$.fn.collapse.noConflict=function(){$.fn.collapse=old
return this}
$(document).on('click.bs.collapse.data-api','[data-toggle="collapse"]',function(e){var $this=$(this)
if(!$this.attr('data-target'))e.preventDefault()
var $target=getTargetFromTrigger($this)
var data=$target.data('bs.collapse')
var option=data?'toggle':$this.data()
Plugin.call($target,option)})}(jQuery);+function($){'use strict';var backdrop='.dropdown-backdrop'
var toggle='[data-toggle="dropdown"]'
var Dropdown=function(element){$(element).on('click.bs.dropdown',this.toggle)}
Dropdown.VERSION='3.4.1'
function getParent($this){var selector=$this.attr('data-target')
if(!selector){selector=$this.attr('href')
selector=selector&&/#[A-Za-z]/.test(selector)&&selector.replace(/.*(?=#[^\s]*$)/,'')}
var $parent=selector!=='#'?$(document).find(selector):null
return $parent&&$parent.length?$parent:$this.parent()}
function clearMenus(e){if(e&&e.which===3)return
$(backdrop).remove()
$(toggle).each(function(){var $this=$(this)
var $parent=getParent($this)
var relatedTarget={relatedTarget:this}
if(!$parent.hasClass('open'))return
if(e&&e.type=='click'&&/input|textarea/i.test(e.target.tagName)&&$.contains($parent[0],e.target))return
$parent.trigger(e=$.Event('hide.bs.dropdown',relatedTarget))
if(e.isDefaultPrevented())return
$this.attr('aria-expanded','false')
$parent.removeClass('open').trigger($.Event('hidden.bs.dropdown',relatedTarget))})}
Dropdown.prototype.toggle=function(e){var $this=$(this)
if($this.is('.disabled, :disabled'))return
var $parent=getParent($this)
var isActive=$parent.hasClass('open')
clearMenus()
if(!isActive){if('ontouchstart'in document.documentElement&&!$parent.closest('.navbar-nav').length){$(document.createElement('div')).addClass('dropdown-backdrop').insertAfter($(this)).on('click',clearMenus)}
var relatedTarget={relatedTarget:this}
$parent.trigger(e=$.Event('show.bs.dropdown',relatedTarget))
if(e.isDefaultPrevented())return
$this.trigger('focus').attr('aria-expanded','true')
$parent.toggleClass('open').trigger($.Event('shown.bs.dropdown',relatedTarget))}
return false}
Dropdown.prototype.keydown=function(e){if(!/(38|40|27|32)/.test(e.which)||/input|textarea/i.test(e.target.tagName))return
var $this=$(this)
e.preventDefault()
e.stopPropagation()
if($this.is('.disabled, :disabled'))return
var $parent=getParent($this)
var isActive=$parent.hasClass('open')
if(!isActive&&e.which!=27||isActive&&e.which==27){if(e.which==27)$parent.find(toggle).trigger('focus')
return $this.trigger('click')}
var desc=' li:not(.disabled):visible a'
var $items=$parent.find('.dropdown-menu'+desc)
if(!$items.length)return
var index=$items.index(e.target)
if(e.which==38&&index>0)index--
if(e.which==40&&index<$items.length-1)index++
if(!~index)index=0
$items.eq(index).trigger('focus')}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.dropdown')
if(!data)$this.data('bs.dropdown',(data=new Dropdown(this)))
if(typeof option=='string')data[option].call($this)})}
var old=$.fn.dropdown
$.fn.dropdown=Plugin
$.fn.dropdown.Constructor=Dropdown
$.fn.dropdown.noConflict=function(){$.fn.dropdown=old
return this}
$(document).on('click.bs.dropdown.data-api',clearMenus).on('click.bs.dropdown.data-api','.dropdown form',function(e){e.stopPropagation()}).on('click.bs.dropdown.data-api',toggle,Dropdown.prototype.toggle).on('keydown.bs.dropdown.data-api',toggle,Dropdown.prototype.keydown).on('keydown.bs.dropdown.data-api','.dropdown-menu',Dropdown.prototype.keydown)}(jQuery);+function($){'use strict';var Modal=function(element,options){this.options=options
this.$body=$(document.body)
this.$element=$(element)
this.$dialog=this.$element.find('.modal-dialog')
this.$backdrop=null
this.isShown=null
this.originalBodyPad=null
this.scrollbarWidth=0
this.ignoreBackdropClick=false
this.fixedContent='.navbar-fixed-top, .navbar-fixed-bottom'
if(this.options.remote){this.$element.find('.modal-content').load(this.options.remote,$.proxy(function(){this.$element.trigger('loaded.bs.modal')},this))}}
Modal.VERSION='3.4.1'
Modal.TRANSITION_DURATION=300
Modal.BACKDROP_TRANSITION_DURATION=150
Modal.DEFAULTS={backdrop:true,keyboard:true,show:true}
Modal.prototype.toggle=function(_relatedTarget){return this.isShown?this.hide():this.show(_relatedTarget)}
Modal.prototype.show=function(_relatedTarget){var that=this
var e=$.Event('show.bs.modal',{relatedTarget:_relatedTarget})
this.$element.trigger(e)
if(this.isShown||e.isDefaultPrevented())return
this.isShown=true
this.checkScrollbar()
this.setScrollbar()
this.$body.addClass('modal-open')
this.escape()
this.resize()
this.$element.on('click.dismiss.bs.modal','[data-dismiss="modal"]',$.proxy(this.hide,this))
this.$dialog.on('mousedown.dismiss.bs.modal',function(){that.$element.one('mouseup.dismiss.bs.modal',function(e){if($(e.target).is(that.$element))that.ignoreBackdropClick=true})})
this.backdrop(function(){var transition=$.support.transition&&that.$element.hasClass('fade')
if(!that.$element.parent().length){that.$element.appendTo(that.$body)}
that.$element.show().scrollTop(0)
that.adjustDialog()
if(transition){that.$element[0].offsetWidth}
that.$element.addClass('in')
that.enforceFocus()
var e=$.Event('shown.bs.modal',{relatedTarget:_relatedTarget})
transition?that.$dialog.one('bsTransitionEnd',function(){that.$element.trigger('focus').trigger(e)}).emulateTransitionEnd(Modal.TRANSITION_DURATION):that.$element.trigger('focus').trigger(e)})}
Modal.prototype.hide=function(e){if(e)e.preventDefault()
e=$.Event('hide.bs.modal')
this.$element.trigger(e)
if(!this.isShown||e.isDefaultPrevented())return
this.isShown=false
this.escape()
this.resize()
$(document).off('focusin.bs.modal')
this.$element.removeClass('in').off('click.dismiss.bs.modal').off('mouseup.dismiss.bs.modal')
this.$dialog.off('mousedown.dismiss.bs.modal')
$.support.transition&&this.$element.hasClass('fade')?this.$element.one('bsTransitionEnd',$.proxy(this.hideModal,this)).emulateTransitionEnd(Modal.TRANSITION_DURATION):this.hideModal()}
Modal.prototype.enforceFocus=function(){$(document).off('focusin.bs.modal').on('focusin.bs.modal',$.proxy(function(e){if(document!==e.target&&this.$element[0]!==e.target&&!this.$element.has(e.target).length){this.$element.trigger('focus')}},this))}
Modal.prototype.escape=function(){if(this.isShown&&this.options.keyboard){this.$element.on('keydown.dismiss.bs.modal',$.proxy(function(e){e.which==27&&this.hide()},this))}else if(!this.isShown){this.$element.off('keydown.dismiss.bs.modal')}}
Modal.prototype.resize=function(){if(this.isShown){$(window).on('resize.bs.modal',$.proxy(this.handleUpdate,this))}else{$(window).off('resize.bs.modal')}}
Modal.prototype.hideModal=function(){var that=this
this.$element.hide()
this.backdrop(function(){that.$body.removeClass('modal-open')
that.resetAdjustments()
that.resetScrollbar()
that.$element.trigger('hidden.bs.modal')})}
Modal.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove()
this.$backdrop=null}
Modal.prototype.backdrop=function(callback){var that=this
var animate=this.$element.hasClass('fade')?'fade':''
if(this.isShown&&this.options.backdrop){var doAnimate=$.support.transition&&animate
this.$backdrop=$(document.createElement('div')).addClass('modal-backdrop '+animate).appendTo(this.$body)
this.$element.on('click.dismiss.bs.modal',$.proxy(function(e){if(this.ignoreBackdropClick){this.ignoreBackdropClick=false
return}
if(e.target!==e.currentTarget)return
this.options.backdrop=='static'?this.$element[0].focus():this.hide()},this))
if(doAnimate)this.$backdrop[0].offsetWidth
this.$backdrop.addClass('in')
if(!callback)return
doAnimate?this.$backdrop.one('bsTransitionEnd',callback).emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION):callback()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass('in')
var callbackRemove=function(){that.removeBackdrop()
callback&&callback()}
$.support.transition&&this.$element.hasClass('fade')?this.$backdrop.one('bsTransitionEnd',callbackRemove).emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION):callbackRemove()}else if(callback){callback()}}
Modal.prototype.handleUpdate=function(){this.adjustDialog()}
Modal.prototype.adjustDialog=function(){var modalIsOverflowing=this.$element[0].scrollHeight>document.documentElement.clientHeight
this.$element.css({paddingLeft:!this.bodyIsOverflowing&&modalIsOverflowing?this.scrollbarWidth:'',paddingRight:this.bodyIsOverflowing&&!modalIsOverflowing?this.scrollbarWidth:''})}
Modal.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:'',paddingRight:''})}
Modal.prototype.checkScrollbar=function(){var fullWindowWidth=window.innerWidth
if(!fullWindowWidth){var documentElementRect=document.documentElement.getBoundingClientRect()
fullWindowWidth=documentElementRect.right-Math.abs(documentElementRect.left)}
this.bodyIsOverflowing=document.body.clientWidth<fullWindowWidth
this.scrollbarWidth=this.measureScrollbar()}
Modal.prototype.setScrollbar=function(){var bodyPad=parseInt((this.$body.css('padding-right')||0),10)
this.originalBodyPad=document.body.style.paddingRight||''
var scrollbarWidth=this.scrollbarWidth
if(this.bodyIsOverflowing){this.$body.css('padding-right',bodyPad+scrollbarWidth)
$(this.fixedContent).each(function(index,element){var actualPadding=element.style.paddingRight
var calculatedPadding=$(element).css('padding-right')
$(element).data('padding-right',actualPadding).css('padding-right',parseFloat(calculatedPadding)+scrollbarWidth+'px')})}}
Modal.prototype.resetScrollbar=function(){this.$body.css('padding-right',this.originalBodyPad)
$(this.fixedContent).each(function(index,element){var padding=$(element).data('padding-right')
$(element).removeData('padding-right')
element.style.paddingRight=padding?padding:''})}
Modal.prototype.measureScrollbar=function(){var scrollDiv=document.createElement('div')
scrollDiv.className='modal-scrollbar-measure'
this.$body.append(scrollDiv)
var scrollbarWidth=scrollDiv.offsetWidth-scrollDiv.clientWidth
this.$body[0].removeChild(scrollDiv)
return scrollbarWidth}
function Plugin(option,_relatedTarget){return this.each(function(){var $this=$(this)
var data=$this.data('bs.modal')
var options=$.extend({},Modal.DEFAULTS,$this.data(),typeof option=='object'&&option)
if(!data)$this.data('bs.modal',(data=new Modal(this,options)))
if(typeof option=='string')data[option](_relatedTarget)
else if(options.show)data.show(_relatedTarget)})}
var old=$.fn.modal
$.fn.modal=Plugin
$.fn.modal.Constructor=Modal
$.fn.modal.noConflict=function(){$.fn.modal=old
return this}
$(document).on('click.bs.modal.data-api','[data-toggle="modal"]',function(e){var $this=$(this)
var href=$this.attr('href')
var target=$this.attr('data-target')||(href&&href.replace(/.*(?=#[^\s]+$)/,''))
var $target=$(document).find(target)
var option=$target.data('bs.modal')?'toggle':$.extend({remote:!/#/.test(href)&&href},$target.data(),$this.data())
if($this.is('a'))e.preventDefault()
$target.one('show.bs.modal',function(showEvent){if(showEvent.isDefaultPrevented())return
$target.one('hidden.bs.modal',function(){$this.is(':visible')&&$this.trigger('focus')})})
Plugin.call($target,option,this)})}(jQuery);+function($){'use strict';var DISALLOWED_ATTRIBUTES=['sanitize','whiteList','sanitizeFn']
var uriAttrs=['background','cite','href','itemtype','longdesc','poster','src','xlink:href']
var ARIA_ATTRIBUTE_PATTERN=/^aria-[\w-]*$/i
var DefaultWhitelist={'*':['class','dir','id','lang','role',ARIA_ATTRIBUTE_PATTERN],a:['target','href','title','rel'],area:[],b:[],br:[],col:[],code:[],div:[],em:[],hr:[],h1:[],h2:[],h3:[],h4:[],h5:[],h6:[],i:[],img:['src','alt','title','width','height'],li:[],ol:[],p:[],pre:[],s:[],small:[],span:[],sub:[],sup:[],strong:[],u:[],ul:[]}
var SAFE_URL_PATTERN=/^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi
var DATA_URL_PATTERN=/^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i
function allowedAttribute(attr,allowedAttributeList){var attrName=attr.nodeName.toLowerCase()
if($.inArray(attrName,allowedAttributeList)!==-1){if($.inArray(attrName,uriAttrs)!==-1){return Boolean(attr.nodeValue.match(SAFE_URL_PATTERN)||attr.nodeValue.match(DATA_URL_PATTERN))}
return true}
var regExp=$(allowedAttributeList).filter(function(index,value){return value instanceof RegExp})
for(var i=0,l=regExp.length;i<l;i++){if(attrName.match(regExp[i])){return true}}
return false}
function sanitizeHtml(unsafeHtml,whiteList,sanitizeFn){if(unsafeHtml.length===0){return unsafeHtml}
if(sanitizeFn&&typeof sanitizeFn==='function'){return sanitizeFn(unsafeHtml)}
if(!document.implementation||!document.implementation.createHTMLDocument){return unsafeHtml}
var createdDocument=document.implementation.createHTMLDocument('sanitization')
createdDocument.body.innerHTML=unsafeHtml
var whitelistKeys=$.map(whiteList,function(el,i){return i})
var elements=$(createdDocument.body).find('*')
for(var i=0,len=elements.length;i<len;i++){var el=elements[i]
var elName=el.nodeName.toLowerCase()
if($.inArray(elName,whitelistKeys)===-1){el.parentNode.removeChild(el)
continue}
var attributeList=$.map(el.attributes,function(el){return el})
var whitelistedAttributes=[].concat(whiteList['*']||[],whiteList[elName]||[])
for(var j=0,len2=attributeList.length;j<len2;j++){if(!allowedAttribute(attributeList[j],whitelistedAttributes)){el.removeAttribute(attributeList[j].nodeName)}}}
return createdDocument.body.innerHTML}
var Tooltip=function(element,options){this.type=null
this.options=null
this.enabled=null
this.timeout=null
this.hoverState=null
this.$element=null
this.inState=null
this.init('tooltip',element,options)}
Tooltip.VERSION='3.4.1'
Tooltip.TRANSITION_DURATION=150
Tooltip.DEFAULTS={animation:true,placement:'top',selector:false,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:'hover focus',title:'',delay:0,html:false,container:false,viewport:{selector:'body',padding:0},sanitize:true,sanitizeFn:null,whiteList:DefaultWhitelist}
Tooltip.prototype.init=function(type,element,options){this.enabled=true
this.type=type
this.$element=$(element)
this.options=this.getOptions(options)
this.$viewport=this.options.viewport&&$(document).find($.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):(this.options.viewport.selector||this.options.viewport))
this.inState={click:false,hover:false,focus:false}
if(this.$element[0]instanceof document.constructor&&!this.options.selector){throw new Error('`selector` option must be specified when initializing '+this.type+' on the window.document object!')}
var triggers=this.options.trigger.split(' ')
for(var i=triggers.length;i--;){var trigger=triggers[i]
if(trigger=='click'){this.$element.on('click.'+this.type,this.options.selector,$.proxy(this.toggle,this))}else if(trigger!='manual'){var eventIn=trigger=='hover'?'mouseenter':'focusin'
var eventOut=trigger=='hover'?'mouseleave':'focusout'
this.$element.on(eventIn+'.'+this.type,this.options.selector,$.proxy(this.enter,this))
this.$element.on(eventOut+'.'+this.type,this.options.selector,$.proxy(this.leave,this))}}
this.options.selector?(this._options=$.extend({},this.options,{trigger:'manual',selector:''})):this.fixTitle()}
Tooltip.prototype.getDefaults=function(){return Tooltip.DEFAULTS}
Tooltip.prototype.getOptions=function(options){var dataAttributes=this.$element.data()
for(var dataAttr in dataAttributes){if(dataAttributes.hasOwnProperty(dataAttr)&&$.inArray(dataAttr,DISALLOWED_ATTRIBUTES)!==-1){delete dataAttributes[dataAttr]}}
options=$.extend({},this.getDefaults(),dataAttributes,options)
if(options.delay&&typeof options.delay=='number'){options.delay={show:options.delay,hide:options.delay}}
if(options.sanitize){options.template=sanitizeHtml(options.template,options.whiteList,options.sanitizeFn)}
return options}
Tooltip.prototype.getDelegateOptions=function(){var options={}
var defaults=this.getDefaults()
this._options&&$.each(this._options,function(key,value){if(defaults[key]!=value)options[key]=value})
return options}
Tooltip.prototype.enter=function(obj){var self=obj instanceof this.constructor?obj:$(obj.currentTarget).data('bs.'+this.type)
if(!self){self=new this.constructor(obj.currentTarget,this.getDelegateOptions())
$(obj.currentTarget).data('bs.'+this.type,self)}
if(obj instanceof $.Event){self.inState[obj.type=='focusin'?'focus':'hover']=true}
if(self.tip().hasClass('in')||self.hoverState=='in'){self.hoverState='in'
return}
clearTimeout(self.timeout)
self.hoverState='in'
if(!self.options.delay||!self.options.delay.show)return self.show()
self.timeout=setTimeout(function(){if(self.hoverState=='in')self.show()},self.options.delay.show)}
Tooltip.prototype.isInStateTrue=function(){for(var key in this.inState){if(this.inState[key])return true}
return false}
Tooltip.prototype.leave=function(obj){var self=obj instanceof this.constructor?obj:$(obj.currentTarget).data('bs.'+this.type)
if(!self){self=new this.constructor(obj.currentTarget,this.getDelegateOptions())
$(obj.currentTarget).data('bs.'+this.type,self)}
if(obj instanceof $.Event){self.inState[obj.type=='focusout'?'focus':'hover']=false}
if(self.isInStateTrue())return
clearTimeout(self.timeout)
self.hoverState='out'
if(!self.options.delay||!self.options.delay.hide)return self.hide()
self.timeout=setTimeout(function(){if(self.hoverState=='out')self.hide()},self.options.delay.hide)}
Tooltip.prototype.show=function(){var e=$.Event('show.bs.'+this.type)
if(this.hasContent()&&this.enabled){this.$element.trigger(e)
var inDom=$.contains(this.$element[0].ownerDocument.documentElement,this.$element[0])
if(e.isDefaultPrevented()||!inDom)return
var that=this
var $tip=this.tip()
var tipId=this.getUID(this.type)
this.setContent()
$tip.attr('id',tipId)
this.$element.attr('aria-describedby',tipId)
if(this.options.animation)$tip.addClass('fade')
var placement=typeof this.options.placement=='function'?this.options.placement.call(this,$tip[0],this.$element[0]):this.options.placement
var autoToken=/\s?auto?\s?/i
var autoPlace=autoToken.test(placement)
if(autoPlace)placement=placement.replace(autoToken,'')||'top'
$tip.detach().css({top:0,left:0,display:'block'}).addClass(placement).data('bs.'+this.type,this)
this.options.container?$tip.appendTo($(document).find(this.options.container)):$tip.insertAfter(this.$element)
this.$element.trigger('inserted.bs.'+this.type)
var pos=this.getPosition()
var actualWidth=$tip[0].offsetWidth
var actualHeight=$tip[0].offsetHeight
if(autoPlace){var orgPlacement=placement
var viewportDim=this.getPosition(this.$viewport)
placement=placement=='bottom'&&pos.bottom+actualHeight>viewportDim.bottom?'top':placement=='top'&&pos.top-actualHeight<viewportDim.top?'bottom':placement=='right'&&pos.right+actualWidth>viewportDim.width?'left':placement=='left'&&pos.left-actualWidth<viewportDim.left?'right':placement
$tip.removeClass(orgPlacement).addClass(placement)}
var calculatedOffset=this.getCalculatedOffset(placement,pos,actualWidth,actualHeight)
this.applyPlacement(calculatedOffset,placement)
var complete=function(){var prevHoverState=that.hoverState
that.$element.trigger('shown.bs.'+that.type)
that.hoverState=null
if(prevHoverState=='out')that.leave(that)}
$.support.transition&&this.$tip.hasClass('fade')?$tip.one('bsTransitionEnd',complete).emulateTransitionEnd(Tooltip.TRANSITION_DURATION):complete()}}
Tooltip.prototype.applyPlacement=function(offset,placement){var $tip=this.tip()
var width=$tip[0].offsetWidth
var height=$tip[0].offsetHeight
var marginTop=parseInt($tip.css('margin-top'),10)
var marginLeft=parseInt($tip.css('margin-left'),10)
if(isNaN(marginTop))marginTop=0
if(isNaN(marginLeft))marginLeft=0
offset.top+=marginTop
offset.left+=marginLeft
$.offset.setOffset($tip[0],$.extend({using:function(props){$tip.css({top:Math.round(props.top),left:Math.round(props.left)})}},offset),0)
$tip.addClass('in')
var actualWidth=$tip[0].offsetWidth
var actualHeight=$tip[0].offsetHeight
if(placement=='top'&&actualHeight!=height){offset.top=offset.top+height-actualHeight}
var delta=this.getViewportAdjustedDelta(placement,offset,actualWidth,actualHeight)
if(delta.left)offset.left+=delta.left
else offset.top+=delta.top
var isVertical=/top|bottom/.test(placement)
var arrowDelta=isVertical?delta.left*2-width+actualWidth:delta.top*2-height+actualHeight
var arrowOffsetPosition=isVertical?'offsetWidth':'offsetHeight'
$tip.offset(offset)
this.replaceArrow(arrowDelta,$tip[0][arrowOffsetPosition],isVertical)}
Tooltip.prototype.replaceArrow=function(delta,dimension,isVertical){this.arrow().css(isVertical?'left':'top',50*(1-delta/dimension)+'%').css(isVertical?'top':'left','')}
Tooltip.prototype.setContent=function(){var $tip=this.tip()
var title=this.getTitle()
if(this.options.html){if(this.options.sanitize){title=sanitizeHtml(title,this.options.whiteList,this.options.sanitizeFn)}
$tip.find('.tooltip-inner').html(title)}else{$tip.find('.tooltip-inner').text(title)}
$tip.removeClass('fade in top bottom left right')}
Tooltip.prototype.hide=function(callback){var that=this
var $tip=$(this.$tip)
var e=$.Event('hide.bs.'+this.type)
function complete(){if(that.hoverState!='in')$tip.detach()
if(that.$element){that.$element.removeAttr('aria-describedby').trigger('hidden.bs.'+that.type)}
callback&&callback()}
this.$element.trigger(e)
if(e.isDefaultPrevented())return
$tip.removeClass('in')
$.support.transition&&$tip.hasClass('fade')?$tip.one('bsTransitionEnd',complete).emulateTransitionEnd(Tooltip.TRANSITION_DURATION):complete()
this.hoverState=null
return this}
Tooltip.prototype.fixTitle=function(){var $e=this.$element
if($e.attr('title')||typeof $e.attr('data-original-title')!='string'){$e.attr('data-original-title',$e.attr('title')||'').attr('title','')}}
Tooltip.prototype.hasContent=function(){return this.getTitle()}
Tooltip.prototype.getPosition=function($element){$element=$element||this.$element
var el=$element[0]
var isBody=el.tagName=='BODY'
var elRect=el.getBoundingClientRect()
if(elRect.width==null){elRect=$.extend({},elRect,{width:elRect.right-elRect.left,height:elRect.bottom-elRect.top})}
var isSvg=window.SVGElement&&el instanceof window.SVGElement
var elOffset=isBody?{top:0,left:0}:(isSvg?null:$element.offset())
var scroll={scroll:isBody?document.documentElement.scrollTop||document.body.scrollTop:$element.scrollTop()}
var outerDims=isBody?{width:$(window).width(),height:$(window).height()}:null
return $.extend({},elRect,scroll,outerDims,elOffset)}
Tooltip.prototype.getCalculatedOffset=function(placement,pos,actualWidth,actualHeight){return placement=='bottom'?{top:pos.top+pos.height,left:pos.left+pos.width/2-actualWidth/2}:placement=='top'?{top:pos.top-actualHeight,left:pos.left+pos.width/2-actualWidth/2}:placement=='left'?{top:pos.top+pos.height/2-actualHeight/2,left:pos.left-actualWidth}:{top:pos.top+pos.height/2-actualHeight/2,left:pos.left+pos.width}}
Tooltip.prototype.getViewportAdjustedDelta=function(placement,pos,actualWidth,actualHeight){var delta={top:0,left:0}
if(!this.$viewport)return delta
var viewportPadding=this.options.viewport&&this.options.viewport.padding||0
var viewportDimensions=this.getPosition(this.$viewport)
if(/right|left/.test(placement)){var topEdgeOffset=pos.top-viewportPadding-viewportDimensions.scroll
var bottomEdgeOffset=pos.top+viewportPadding-viewportDimensions.scroll+actualHeight
if(topEdgeOffset<viewportDimensions.top){delta.top=viewportDimensions.top-topEdgeOffset}else if(bottomEdgeOffset>viewportDimensions.top+viewportDimensions.height){delta.top=viewportDimensions.top+viewportDimensions.height-bottomEdgeOffset}}else{var leftEdgeOffset=pos.left-viewportPadding
var rightEdgeOffset=pos.left+viewportPadding+actualWidth
if(leftEdgeOffset<viewportDimensions.left){delta.left=viewportDimensions.left-leftEdgeOffset}else if(rightEdgeOffset>viewportDimensions.right){delta.left=viewportDimensions.left+viewportDimensions.width-rightEdgeOffset}}
return delta}
Tooltip.prototype.getTitle=function(){var title
var $e=this.$element
var o=this.options
title=$e.attr('data-original-title')||(typeof o.title=='function'?o.title.call($e[0]):o.title)
return title}
Tooltip.prototype.getUID=function(prefix){do prefix+=~~(Math.random()*1000000)
while(document.getElementById(prefix))
return prefix}
Tooltip.prototype.tip=function(){if(!this.$tip){this.$tip=$(this.options.template)
if(this.$tip.length!=1){throw new Error(this.type+' `template` option must consist of exactly 1 top-level element!')}}
return this.$tip}
Tooltip.prototype.arrow=function(){return(this.$arrow=this.$arrow||this.tip().find('.tooltip-arrow'))}
Tooltip.prototype.enable=function(){this.enabled=true}
Tooltip.prototype.disable=function(){this.enabled=false}
Tooltip.prototype.toggleEnabled=function(){this.enabled=!this.enabled}
Tooltip.prototype.toggle=function(e){var self=this
if(e){self=$(e.currentTarget).data('bs.'+this.type)
if(!self){self=new this.constructor(e.currentTarget,this.getDelegateOptions())
$(e.currentTarget).data('bs.'+this.type,self)}}
if(e){self.inState.click=!self.inState.click
if(self.isInStateTrue())self.enter(self)
else self.leave(self)}else{self.tip().hasClass('in')?self.leave(self):self.enter(self)}}
Tooltip.prototype.destroy=function(){var that=this
clearTimeout(this.timeout)
this.hide(function(){that.$element.off('.'+that.type).removeData('bs.'+that.type)
if(that.$tip){that.$tip.detach()}
that.$tip=null
that.$arrow=null
that.$viewport=null
that.$element=null})}
Tooltip.prototype.sanitizeHtml=function(unsafeHtml){return sanitizeHtml(unsafeHtml,this.options.whiteList,this.options.sanitizeFn)}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.tooltip')
var options=typeof option=='object'&&option
if(!data&&/destroy|hide/.test(option))return
if(!data)$this.data('bs.tooltip',(data=new Tooltip(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.tooltip
$.fn.tooltip=Plugin
$.fn.tooltip.Constructor=Tooltip
$.fn.tooltip.noConflict=function(){$.fn.tooltip=old
return this}}(jQuery);+function($){'use strict';var Popover=function(element,options){this.init('popover',element,options)}
if(!$.fn.tooltip)throw new Error('Popover requires tooltip.js')
Popover.VERSION='3.4.1'
Popover.DEFAULTS=$.extend({},$.fn.tooltip.Constructor.DEFAULTS,{placement:'right',trigger:'click',content:'',template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'})
Popover.prototype=$.extend({},$.fn.tooltip.Constructor.prototype)
Popover.prototype.constructor=Popover
Popover.prototype.getDefaults=function(){return Popover.DEFAULTS}
Popover.prototype.setContent=function(){var $tip=this.tip()
var title=this.getTitle()
var content=this.getContent()
if(this.options.html){var typeContent=typeof content
if(this.options.sanitize){title=this.sanitizeHtml(title)
if(typeContent==='string'){content=this.sanitizeHtml(content)}}
$tip.find('.popover-title').html(title)
$tip.find('.popover-content').children().detach().end()[typeContent==='string'?'html':'append'](content)}else{$tip.find('.popover-title').text(title)
$tip.find('.popover-content').children().detach().end().text(content)}
$tip.removeClass('fade top bottom left right in')
if(!$tip.find('.popover-title').html())$tip.find('.popover-title').hide()}
Popover.prototype.hasContent=function(){return this.getTitle()||this.getContent()}
Popover.prototype.getContent=function(){var $e=this.$element
var o=this.options
return $e.attr('data-content')||(typeof o.content=='function'?o.content.call($e[0]):o.content)}
Popover.prototype.arrow=function(){return(this.$arrow=this.$arrow||this.tip().find('.arrow'))}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.popover')
var options=typeof option=='object'&&option
if(!data&&/destroy|hide/.test(option))return
if(!data)$this.data('bs.popover',(data=new Popover(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.popover
$.fn.popover=Plugin
$.fn.popover.Constructor=Popover
$.fn.popover.noConflict=function(){$.fn.popover=old
return this}}(jQuery);+function($){'use strict';function ScrollSpy(element,options){this.$body=$(document.body)
this.$scrollElement=$(element).is(document.body)?$(window):$(element)
this.options=$.extend({},ScrollSpy.DEFAULTS,options)
this.selector=(this.options.target||'')+' .nav li > a'
this.offsets=[]
this.targets=[]
this.activeTarget=null
this.scrollHeight=0
this.$scrollElement.on('scroll.bs.scrollspy',$.proxy(this.process,this))
this.refresh()
this.process()}
ScrollSpy.VERSION='3.4.1'
ScrollSpy.DEFAULTS={offset:10}
ScrollSpy.prototype.getScrollHeight=function(){return this.$scrollElement[0].scrollHeight||Math.max(this.$body[0].scrollHeight,document.documentElement.scrollHeight)}
ScrollSpy.prototype.refresh=function(){var that=this
var offsetMethod='offset'
var offsetBase=0
this.offsets=[]
this.targets=[]
this.scrollHeight=this.getScrollHeight()
if(!$.isWindow(this.$scrollElement[0])){offsetMethod='position'
offsetBase=this.$scrollElement.scrollTop()}
this.$body.find(this.selector).map(function(){var $el=$(this)
var href=$el.data('target')||$el.attr('href')
var $href=/^#./.test(href)&&$(href)
return($href&&$href.length&&$href.is(':visible')&&[[$href[offsetMethod]().top+offsetBase,href]])||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){that.offsets.push(this[0])
that.targets.push(this[1])})}
ScrollSpy.prototype.process=function(){var scrollTop=this.$scrollElement.scrollTop()+this.options.offset
var scrollHeight=this.getScrollHeight()
var maxScroll=this.options.offset+scrollHeight-this.$scrollElement.height()
var offsets=this.offsets
var targets=this.targets
var activeTarget=this.activeTarget
var i
if(this.scrollHeight!=scrollHeight){this.refresh()}
if(scrollTop>=maxScroll){return activeTarget!=(i=targets[targets.length-1])&&this.activate(i)}
if(activeTarget&&scrollTop<offsets[0]){this.activeTarget=null
return this.clear()}
for(i=offsets.length;i--;){activeTarget!=targets[i]&&scrollTop>=offsets[i]&&(offsets[i+1]===undefined||scrollTop<offsets[i+1])&&this.activate(targets[i])}}
ScrollSpy.prototype.activate=function(target){this.activeTarget=target
this.clear()
var selector=this.selector+'[data-target="'+target+'"],'+
this.selector+'[href="'+target+'"]'
var active=$(selector).parents('li').addClass('active')
if(active.parent('.dropdown-menu').length){active=active.closest('li.dropdown').addClass('active')}
active.trigger('activate.bs.scrollspy')}
ScrollSpy.prototype.clear=function(){$(this.selector).parentsUntil(this.options.target,'.active').removeClass('active')}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.scrollspy')
var options=typeof option=='object'&&option
if(!data)$this.data('bs.scrollspy',(data=new ScrollSpy(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.scrollspy
$.fn.scrollspy=Plugin
$.fn.scrollspy.Constructor=ScrollSpy
$.fn.scrollspy.noConflict=function(){$.fn.scrollspy=old
return this}
$(window).on('load.bs.scrollspy.data-api',function(){$('[data-spy="scroll"]').each(function(){var $spy=$(this)
Plugin.call($spy,$spy.data())})})}(jQuery);+function($){'use strict';var Tab=function(element){this.element=$(element)}
Tab.VERSION='3.4.1'
Tab.TRANSITION_DURATION=150
Tab.prototype.show=function(){var $this=this.element
var $ul=$this.closest('ul:not(.dropdown-menu)')
var selector=$this.data('target')
if(!selector){selector=$this.attr('href')
selector=selector&&selector.replace(/.*(?=#[^\s]*$)/,'')}
if($this.parent('li').hasClass('active'))return
var $previous=$ul.find('.active:last a')
var hideEvent=$.Event('hide.bs.tab',{relatedTarget:$this[0]})
var showEvent=$.Event('show.bs.tab',{relatedTarget:$previous[0]})
$previous.trigger(hideEvent)
$this.trigger(showEvent)
if(showEvent.isDefaultPrevented()||hideEvent.isDefaultPrevented())return
var $target=$(document).find(selector)
this.activate($this.closest('li'),$ul)
this.activate($target,$target.parent(),function(){$previous.trigger({type:'hidden.bs.tab',relatedTarget:$this[0]})
$this.trigger({type:'shown.bs.tab',relatedTarget:$previous[0]})})}
Tab.prototype.activate=function(element,container,callback){var $active=container.find('> .active')
var transition=callback&&$.support.transition&&($active.length&&$active.hasClass('fade')||!!container.find('> .fade').length)
function next(){$active.removeClass('active').find('> .dropdown-menu > .active').removeClass('active').end().find('[data-toggle="tab"]').attr('aria-expanded',false)
element.addClass('active').find('[data-toggle="tab"]').attr('aria-expanded',true)
if(transition){element[0].offsetWidth
element.addClass('in')}else{element.removeClass('fade')}
if(element.parent('.dropdown-menu').length){element.closest('li.dropdown').addClass('active').end().find('[data-toggle="tab"]').attr('aria-expanded',true)}
callback&&callback()}
$active.length&&transition?$active.one('bsTransitionEnd',next).emulateTransitionEnd(Tab.TRANSITION_DURATION):next()
$active.removeClass('in')}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.tab')
if(!data)$this.data('bs.tab',(data=new Tab(this)))
if(typeof option=='string')data[option]()})}
var old=$.fn.tab
$.fn.tab=Plugin
$.fn.tab.Constructor=Tab
$.fn.tab.noConflict=function(){$.fn.tab=old
return this}
var clickHandler=function(e){e.preventDefault()
Plugin.call($(this),'show')}
$(document).on('click.bs.tab.data-api','[data-toggle="tab"]',clickHandler).on('click.bs.tab.data-api','[data-toggle="pill"]',clickHandler)}(jQuery);+function($){'use strict';var Affix=function(element,options){this.options=$.extend({},Affix.DEFAULTS,options)
var target=this.options.target===Affix.DEFAULTS.target?$(this.options.target):$(document).find(this.options.target)
this.$target=target.on('scroll.bs.affix.data-api',$.proxy(this.checkPosition,this)).on('click.bs.affix.data-api',$.proxy(this.checkPositionWithEventLoop,this))
this.$element=$(element)
this.affixed=null
this.unpin=null
this.pinnedOffset=null
this.checkPosition()}
Affix.VERSION='3.4.1'
Affix.RESET='affix affix-top affix-bottom'
Affix.DEFAULTS={offset:0,target:window}
Affix.prototype.getState=function(scrollHeight,height,offsetTop,offsetBottom){var scrollTop=this.$target.scrollTop()
var position=this.$element.offset()
var targetHeight=this.$target.height()
if(offsetTop!=null&&this.affixed=='top')return scrollTop<offsetTop?'top':false
if(this.affixed=='bottom'){if(offsetTop!=null)return(scrollTop+this.unpin<=position.top)?false:'bottom'
return(scrollTop+targetHeight<=scrollHeight-offsetBottom)?false:'bottom'}
var initializing=this.affixed==null
var colliderTop=initializing?scrollTop:position.top
var colliderHeight=initializing?targetHeight:height
if(offsetTop!=null&&scrollTop<=offsetTop)return'top'
if(offsetBottom!=null&&(colliderTop+colliderHeight>=scrollHeight-offsetBottom))return'bottom'
return false}
Affix.prototype.getPinnedOffset=function(){if(this.pinnedOffset)return this.pinnedOffset
this.$element.removeClass(Affix.RESET).addClass('affix')
var scrollTop=this.$target.scrollTop()
var position=this.$element.offset()
return(this.pinnedOffset=position.top-scrollTop)}
Affix.prototype.checkPositionWithEventLoop=function(){setTimeout($.proxy(this.checkPosition,this),1)}
Affix.prototype.checkPosition=function(){if(!this.$element.is(':visible'))return
var height=this.$element.height()
var offset=this.options.offset
var offsetTop=offset.top
var offsetBottom=offset.bottom
var scrollHeight=Math.max($(document).height(),$(document.body).height())
if(typeof offset!='object')offsetBottom=offsetTop=offset
if(typeof offsetTop=='function')offsetTop=offset.top(this.$element)
if(typeof offsetBottom=='function')offsetBottom=offset.bottom(this.$element)
var affix=this.getState(scrollHeight,height,offsetTop,offsetBottom)
if(this.affixed!=affix){if(this.unpin!=null)this.$element.css('top','')
var affixType='affix'+(affix?'-'+affix:'')
var e=$.Event(affixType+'.bs.affix')
this.$element.trigger(e)
if(e.isDefaultPrevented())return
this.affixed=affix
this.unpin=affix=='bottom'?this.getPinnedOffset():null
this.$element.removeClass(Affix.RESET).addClass(affixType).trigger(affixType.replace('affix','affixed')+'.bs.affix')}
if(affix=='bottom'){this.$element.offset({top:scrollHeight-height-offsetBottom})}}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.affix')
var options=typeof option=='object'&&option
if(!data)$this.data('bs.affix',(data=new Affix(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.affix
$.fn.affix=Plugin
$.fn.affix.Constructor=Affix
$.fn.affix.noConflict=function(){$.fn.affix=old
return this}
$(window).on('load',function(){$('[data-spy="affix"]').each(function(){var $spy=$(this)
var data=$spy.data()
data.offset=data.offset||{}
if(data.offsetBottom!=null)data.offset.bottom=data.offsetBottom
if(data.offsetTop!=null)data.offset.top=data.offsetTop
Plugin.call($spy,data)})})}(jQuery);;function loadingScript(){$.ajaxSetup({beforeSend:function(){var background=$("<div>",{"class":"ds-background"});var loading=$("<div>",{"class":"ds-loading"});$(background).css("top","0").css("left","0");$(loading).css("top",($(window).height()/2)-32).css("left",($(document).width()/2)-32);$("body").append(background);$("body").append(loading);},complete:function(){$(".ds-loading").detach();$(".ds-background").detach();}});}
$(".product_main_page_toggle_admin").change(function(){var id=$(this).data('id');var status=0;if(this.checked){status=1;}
$.ajax({type:'POST',url:"/admin/products-main-pages/product-status",data:{status:status,id:id},success:function(res){if(!res){alert("Ошибка!");}}});});$(".user-radio-select").change(function(){var value=$(this).find('input').val();loadingScript();$.ajax({data:{value:value},type:'post',url:'/admin/order/user-info',success:function(data){$('.user-select').html(data);}});});$(document).on('click','.product-filter-modal',function(){loadingScript();$.ajax({type:'post',data:gets,url:'/admin/products/products-filter',success:function(data){$('#product-filter-modal .modal-body').html(data);$('#product-filter-modal').modal();},});return false;});var gets=(function(){var a=window.location.search;var b=new Object();a=a.substring(1).split("&");for(var i=0;i<a.length;i++){c=a[i].split("=");b[c[0]]=c[1];}
return b;})();$("#user-search").change(function(){loadingScript();$('.user-info').html('');});(function(e){e.fn.extend({slimScroll:function(g){var a=e.extend({width:"auto",height:"250px",size:"7px",color:"#000",position:"right",distance:"1px",start:"top",opacity:.4,alwaysVisible:!1,disableFadeOut:!1,railVisible:!1,railColor:"#333",railOpacity:.2,railDraggable:!0,railClass:"slimScrollRail",barClass:"slimScrollBar",wrapperClass:"slimScrollDiv",allowPageScroll:!1,wheelStep:20,touchScrollStep:200,borderRadius:"7px",railBorderRadius:"7px"},g);this.each(function(){function v(d){if(r){d=d||window.event;var c=0;d.wheelDelta&&(c=-d.wheelDelta/120);d.detail&&(c=d.detail/3);e(d.target||d.srcTarget||d.srcElement).closest("."+a.wrapperClass).is(b.parent())&&m(c,!0);d.preventDefault&&!k&&d.preventDefault();k||(d.returnValue=!1)}}
function m(d,e,g){k=!1;var f=d,h=b.outerHeight()-c.outerHeight();e&&(f=parseInt(c.css("top"))+d*parseInt(a.wheelStep)/100*c.outerHeight(),f=Math.min(Math.max(f,0),h),f=0<d?Math.ceil(f):Math.floor(f),c.css({top:f+"px"}));l=parseInt(c.css("top"))/(b.outerHeight()-c.outerHeight());f=l*(b[0].scrollHeight-b.outerHeight());g&&(f=d,d=f/b[0].scrollHeight*b.outerHeight(),d=Math.min(Math.max(d,0),h),c.css({top:d+"px"}));b.scrollTop(f);b.trigger("slimscrolling",~~f);w();p()}
function x(){u=Math.max(b.outerHeight()/b[0].scrollHeight*b.outerHeight(),30);c.css({height:u+"px"});var a=u==b.outerHeight()?"none":"block";c.css({display:a})}
function w(){x();clearTimeout(B);l==~~l?(k=a.allowPageScroll,C!=l&&b.trigger("slimscroll",0==~~l?"top":"bottom")):k=!1;C=l;u>=b.outerHeight()?k=!0:(c.stop(!0,!0).fadeIn("fast"),a.railVisible&&h.stop(!0,!0).fadeIn("fast"))}
function p(){a.alwaysVisible||(B=setTimeout(function(){a.disableFadeOut&&r||y||z||(c.fadeOut("slow"),h.fadeOut("slow"))},1E3))}
var r,y,z,B,A,u,l,C,k=!1,b=e(this);if(b.parent().hasClass(a.wrapperClass)){var n=b.scrollTop(),c=b.closest("."+a.barClass),h=b.closest("."+a.railClass);x();if(e.isPlainObject(g)){if("height"in g&&"auto"==g.height){b.parent().css("height","auto");b.css("height","auto");var q=b.parent().parent().height();b.parent().css("height",q);b.css("height",q)}
if("scrollTo"in g)n=parseInt(a.scrollTo);else if("scrollBy"in g)n+=parseInt(a.scrollBy);else if("destroy"in g){c.remove();h.remove();b.unwrap();return}
m(n,!1,!0)}}else if(!(e.isPlainObject(g)&&"destroy"in g)){a.height="auto"==a.height?b.parent().height():a.height;n=e("<div></div>").addClass(a.wrapperClass).css({position:"relative",overflow:"hidden",width:a.width,height:a.height});b.css({overflow:"hidden",width:a.width,height:a.height});var h=e("<div></div>").addClass(a.railClass).css({width:a.size,height:"100%",position:"absolute",top:0,display:a.alwaysVisible&&a.railVisible?"block":"none","border-radius":a.railBorderRadius,background:a.railColor,opacity:a.railOpacity,zIndex:90}),c=e("<div></div>").addClass(a.barClass).css({background:a.color,width:a.size,position:"absolute",top:0,opacity:a.opacity,display:a.alwaysVisible?"block":"none","border-radius":a.borderRadius,BorderRadius:a.borderRadius,MozBorderRadius:a.borderRadius,WebkitBorderRadius:a.borderRadius,zIndex:99}),q="right"==a.position?{right:a.distance}:{left:a.distance};h.css(q);c.css(q);b.wrap(n);b.parent().append(c);b.parent().append(h);a.railDraggable&&c.bind("mousedown",function(a){var b=e(document);z=!0;t=parseFloat(c.css("top"));pageY=a.pageY;b.bind("mousemove.slimscroll",function(a){currTop=t+a.pageY-pageY;c.css("top",currTop);m(0,c.position().top,!1)});b.bind("mouseup.slimscroll",function(a){z=!1;p();b.unbind(".slimscroll")});return!1}).bind("selectstart.slimscroll",function(a){a.stopPropagation();a.preventDefault();return!1});h.hover(function(){w()},function(){p()});c.hover(function(){y=!0},function(){y=!1});b.hover(function(){r=!0;w();p()},function(){r=!1;p()});b.bind("touchstart",function(a,b){a.originalEvent.touches.length&&(A=a.originalEvent.touches[0].pageY)});b.bind("touchmove",function(b){k||b.originalEvent.preventDefault();b.originalEvent.touches.length&&(m((A-b.originalEvent.touches[0].pageY)/a.touchScrollStep,!0),A=b.originalEvent.touches[0].pageY)});x();"bottom"===a.start?(c.css({top:b.outerHeight()-c.outerHeight()}),m(0,!0)):"top"!==a.start&&(m(e(a.start).position().top,null,!0),a.alwaysVisible||c.hide());window.addEventListener?(this.addEventListener("DOMMouseScroll",v,!1),this.addEventListener("mousewheel",v,!1)):document.attachEvent("onmousewheel",v)}});return this}});e.fn.extend({slimscroll:e.fn.slimScroll})})(jQuery);$(function(){"use strict";$.pushMenu={activate:function(toggleBtn){$(toggleBtn).on('click',function(e){e.preventDefault();if($(window).width()>(767)){if($("body").hasClass('sidebar-collapse')){$("body").removeClass('sidebar-collapse').trigger('expanded.pushMenu');}else{$("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');}}
else{if($("body").hasClass('sidebar-open')){$("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');}else{$("body").addClass('sidebar-open').trigger('expanded.pushMenu');}}
if($('body').hasClass('fixed')&&$('body').hasClass('sidebar-mini')&&$('body').hasClass('sidebar-collapse')){$('.sidebar').css("overflow","visible");$('.main-sidebar').find(".slimScrollDiv").css("overflow","visible");}
if($('body').hasClass('only-sidebar')){$('.sidebar').css("overflow","visible");$('.main-sidebar').find(".slimScrollDiv").css("overflow","visible");};});$(".content-wrapper").click(function(){if($(window).width()<=(767)&&$("body").hasClass("sidebar-open")){$("body").removeClass('sidebar-open');}});}};$.tree=function(menu){var _this=this;var animationSpeed=200;$(document).on('click',menu+' li a',function(e){var $this=$(this);var checkElement=$this.next();if((checkElement.is('.treeview-menu'))&&(checkElement.is(':visible'))){checkElement.slideUp(animationSpeed,function(){checkElement.removeClass('menu-open');});checkElement.parent("li").removeClass("active");}
else if((checkElement.is('.treeview-menu'))&&(!checkElement.is(':visible'))){var parent=$this.parents('ul').first();var ul=parent.find('ul:visible').slideUp(animationSpeed);ul.removeClass('menu-open');var parent_li=$this.parent("li");checkElement.slideDown(animationSpeed,function(){checkElement.addClass('menu-open');parent.find('li.active').removeClass('active');parent_li.addClass('active');});}
if(checkElement.is('.treeview-menu')){e.preventDefault();}});};$.tree('.sidebar');$.pushMenu.activate("[data-toggle='offcanvas']");$("[data-toggle='tooltip']").tooltip();$('.login-content [data-toggle="flip"]').click(function(){$('.login-box').toggleClass('flipped');return false;});$('.sidebar').slimScroll({height:($(window).height()-$(".main-header").height())+"px",color:"rgba(0,0,0,0.8)",size:"3px"});});$('.search-on').change(function(){var search_on=$(this).find(':selected').val();if(search_on=='0'){$('.search_id').hide();$('.search_name').hide();$('.search_category').hide();}else if(search_on=='1'){$('.search_id').show();$('.search_name').hide();$('.search_category').hide();}else if(search_on=='2'){$('.search_id').hide();$('.search_name').show();$('.search_category').hide();}else if(search_on=='3'){$('.search_id').hide();$('.search_name').hide();$('.search_category').show();}
$('#productssearch-id').val('');$('#productssearch-name').val('');$('#productssearch-category_id').val('');});$(window).load(function(){var id=$('.select-sity').val();var postData={id:id};$.ajax({type:'POST',async:true,url:"/seller/setting/sity/",data:postData,dataType:'json',success:function(res){$(".field-shops-shopsity_name").attr('style','display: inline');$('#shops-sity_name').empty();$.each(res['sity'],function(index,param){if(res['shopsity']==param['sity_name']){var select=" selected ";}else{var select="";}
$('#shops-shopsity_name').append('<option value="'+param['sity_name']+'"'+select+'>'+param['sity_name']+'</option>');return;});}})});$('.select-sity').change(function(){var id=$(this).val();var postData={id:id};$.ajax({type:'POST',async:true,url:"/seller/setting/sity/",data:postData,dataType:'json',success:function(res){$(".field-shops-shopsity_name").attr('style','display: inline');$('#shops-shopsity_name').empty();$.each(res['sity'],function(index,param){$('#shops-shopsity_name').append('<option value="'+param['sity_name']+'">'+param['sity_name']+'</option>');return;});}})});$(function(){$("ul.list").on('click','.action',function(){var self=$(this);if(!self.attr("action")){return true;}
if(self.attr("confirm")&&!confirm(self.attr("confirm"))){return false;}
var form=$(this).parents('form');form.find('input[name=id]').val(self.parents("li").attr("row_id"));form.find('input[name=action]').val(self.attr("action"));form.find('input[name=module]').val(self.attr("module"));form.submit();return false;});$('.select-all + label').click(function(){if($('.select-all').is(':checked')){$('.checkbox').removeClass('checked').find('input:checked').click();}else{$('.checkbox').addClass('checked').find('input:not(:checked)').click();}
$('.select-all').click();});$('select[name=group_action]').change(function(){var $checked=$(this).find(':selected');$('.action-popup').css('padding-left',$('.select-all + label').outerWidth()+16);$('select[name=group_action]').find('option:nth-child('+($checked.index()+1)+')').prop('selected',true);$('.action-popup').each(function(){if($(this).is('.dop_'+$checked.attr("value"))){$(this).slideDown('fast');}else{$(this).slideUp('fast');}});});$(document).on('click',".group_actions",function(){if(!$("input[name='ids[]']:checked").length)return false;var self=$("select[name=group_action] option:selected");if(self.attr("confirm")&&!confirm(self.attr("confirm"))){return false;}
var form=$(this).parents('form');form.find('input[name=module]').val(self.attr('module'));form.find('input[name=action]').val($("select[name=group_action]").val());form.submit();return false;});$(document).on('change',".group_menu_cat_id",function(){if($(this).is(":checked")){$(".group_menu_cat_id[value="+$(this).attr("value")+"]").prop("checked",true);}else{$(".group_menu_cat_id[value="+$(this).attr("value")+"]").prop("checked",false);}});$('.action-popup input').change(function(){var name=$(this).attr("name");if($(this).is(':checked'))
$('.action-popup').find('input[name="'+name+'""]:nth-child('+($(this).index()+1)+')').attr('checked','checked');else
$('.action-popup').find('input[name="'+name+'""]:nth-child('+($(this).index()+1)+')').removeAttr('checked');});$('.action-popup select').change(function(){var $checked=$(this).find(':selected');$('.action-popup select[name="'+$(this).attr("name")+'"]').find('option:nth-child('+($checked.index()+1)+')').attr('selected','selected');$('.action-popup select[name="'+$(this).attr("name")+'"] ~ .jq-selectbox__dropdown').each(function(){$(this).find('li').removeClass('sel selected').eq($checked.index()).addClass('sel').addClass('selected');});$('.action-popup select[name="'+$(this).attr("name")+'"] ~ .jq-selectbox__select').each(function(){$(this).find('.jq-selectbox__select-text').text($checked.text())});});$(document).on('click',".change_nastr",function(){diafan_ajax.init({data:{nastr:$(this).prev('input[name=nastr]').val(),action:'change_nastr'}});return false;});$('[name*="nastr"]').keyup(function(e){if(e.keyCode==13)
$(this).next('.change_nastr').click();});var fast_edit={old_value:false,element:false,init:function(){$(document).on('focus',this.element,function(){fast_edit.focus($(this));}).on('blur',this.element,function(){fast_edit.blur($(this));}).on('keyup',this.element,function(e){fast_edit.keyup(e,$(this));});},blur:function($this){$this.parent().removeClass('focus');},focus:function($this){if($this.attr("type")=='radio'){fast_edit.ajax($this);}else{$this.parent().addClass('focus');}},keyup:function(e,$this){if(!(e.keyCode==37)&&!(e.keyCode==38)&&!(e.keyCode==39)&&!(e.keyCode==40)){var $item=$this.closest('.item__field');$item.addClass('change').removeClass('success');$item.find('.item__field__cover span').text($this.val());if(e.keyCode==13){$item.addClass('success').removeClass('change');fast_edit.ajax($this);}}},ajax:function(e){diafan_ajax.init({data:{action:'fast_save',name:e.attr('name'),value:e.val(),type:e.attr('type'),id:e.attr('row_id')},success:function(response){if(response.res==false){e.val(fast_edit.old_value);}
if(e.attr('reload')){window.location.href=document.location;}}});}}
fast_edit.element=".fast_edit textarea, .fast_edit input";fast_edit.init();$(document).on("click",".item__toggle .fa",function(){var $item=$(this).closest('.item');if(!$item.length){$item=$(this).closest('.action-box');$(".item .item__toggle .fa").each(function(){tree_plus($(this).closest('.item'),$item.hasClass('active_all'),true);});if($item.hasClass('active_all')){$('.action-box').removeClass('active_all')}else{$('.action-box').addClass('active_all')}}else{tree_plus($item,$item.hasClass('active'),false);}});$(document).on("keydown",'.item__field input',function(e){if(e.keyCode==13){e.preventDefault();}});$(document).on("keyup",'.item__field input',function(e){if($(this).is('.numtext')){$(this).val(formatStr($(this).val()));$('.item__field__cover span',$(this).parents('.item__field')).text($(this).val());}});$(document).on("click",'.item__field .fa-check-circle',function(){$(this).closest('.item__field').addClass('success').removeClass('change');});$(document).on("click",'.item__field__cover',function(){$(this).parent().find('input').focus();$(this).parent().addClass('focus');});if($(window).width()<1023){$('.item__ui').addClass('item__ui_adapt')
$('.item__ui').click(function(e){if($(this).hasClass('item__ui_adapt'))
e.preventDefault()});$('.item__in').click(function(){$('.item__in .item__ui').addClass('item__ui_adapt');$(this).find('.item__ui_adapt').removeClass('item__ui_adapt');});}
$('.item .text').each(function(){if($(this).find('.fast_edit').length){return;}
$(this).html(truncate($(this).html(),240));});$('.item .name').each(function(){if($(this).find('> a').length)
$(this).find('> a').text(truncate($(this).find('> a').text(),150));else
$(this).text(truncate($(this).text(),150));});$('.item__adapt').click(function(){var $this=$(this);if($this.hasClass('active')){$this.css('padding-top',0).removeClass('active').closest('.item__in').removeClass('item__in_adaptive').find('.item__unit').css('margin-top',0);}else{$this.css('padding-top',(($(this).height()-18)/2)-2).addClass('active').closest('.item__in').addClass('item__in_adaptive').find('.item__unit').css('margin-top','-'+
($(this).closest('.item__in').find('.item__unit').height()/2)+'px');}});init_list();});function tree_plus($item,$plus,$all){if($plus){$item.find(' > .list > .item,  > .paginator').slideUp('fast',function(){$item.removeClass('active');});}else{if($item.find(' > .list > .item').length){$item.find(' > .list > .item,  > .paginator').slideDown('fast',function(){$item.addClass('active');});}else{var id=$item.attr("row_id");$.ajax({url:window.location.href,type:"POST",dataType:"json",cache:false,async:false,data:{ajax:"expand",parent_id:id,check_hash_user:$('.check_hash_user').text()},success:function(response){if(response.hash){$('input[name=check_hash_user]').val(response.hash);$('.check_hash_user').text(response.hash);}
if(response.html){$item.addClass('active');$item.append(prepare(response.html));init_list();sort_items.start();}
if($all){$(".item .item__toggle .fa",$item).each(function(){tree_plus($(this).closest('.item'),$plus,true);});}}});}}}
function init_list(){$('.numtext').each(function(){$(this).val(formatStr($(this).val()));});$('.item__field').each(function(){var $this=$(this);$this.find('.item__field__cover span').text($this.find('input').val());});$('.item__field__cover').click(function(){var $this=$(this);if(!$this.hasClass('visible')){$this.addClass('visible');setCaretPosition($this.parent().find('input').get(0),$this.parent().find('input').val().length+1)}});do_auto_width();}
function formatStr(str){str=str.toString().replace(/(\.(.*))/g,'');str=str.replace(/\s+/g,'');var arr=str.split('');var str_temp='';if(str.length>3){for(var i=arr.length-1,j=1;i>=0;i--,j++){str_temp=arr[i]+str_temp;if(j%3==0&&i!=0){str_temp=' '+str_temp;}}
return str_temp;}else{return str;}}
function truncate(str,max){return(str.length>max)?str.substring(0,max-3)+'...':str;};function setCaretPosition(ctrl,pos){if(ctrl.setSelectionRange){ctrl.setSelectionRange(pos,pos);}else if(ctrl.createTextRange){var range=ctrl.createTextRange();range.collapse(true);range.moveEnd('character',pos);range.moveStart('character',pos);}}
function do_auto_width(){$('.do_auto_width').each(function(){var $this=$(this),$item_in=$(this).find('> .item > .item__in'),arr_width=[];for(var i=0;i<$item_in.eq(1).find('> *').length;i++){arr_width.push(0)}
$item_in.each(function(){$i=0;$(this).find('> *').each(function(){if(arr_width[$i]<$(this).outerWidth()){arr_width[$i]=$(this).outerWidth();}
$i++;});});$item_in.each(function(){$i=0;$(this).find('> *').each(function(){$(this).outerWidth(arr_width[$i]);$i++;});});});$('.item__th').each(function(){var $this=$(this),index=$(this).index(),$sample=($this.closest('.list_pages').length)?$('.list_pages .item .item:first-child'):$('.item:nth-child(2)');$this.outerWidth($sample.find('.item__in > *').eq(index).outerWidth()).css({'padding-left':$sample.find('.item__in > *').eq(index).css('padding-left'),'padding-right':$sample.find('.item__in > *').eq(index).css('padding-right')});$(window).resize();});}
$('form.form-index-content').on('click','.add-position',function(event){loadingScript();var countPosition=$('.productMainPages-form div.position-list').length;$.ajax({type:"POST",url:'/admin/products-main-pages/add-position',data:{index:countPosition},success:function(response){if(response){$('.productMainPages-form').append(response);}},error:function(response){console.log(response);}});return false;});$('form.form-product-color').on('click','.add-color',function(event){loadingScript();var countPosition=$('.productMainPages-form div.position-list').length;$.ajax({type:"POST",url:'/admin/products-main-pages/add-position',data:{index:countPosition},success:function(response){if(response){$('.productMainPages-form').append(response);}},error:function(response){console.log(response);}});return false;});$('form.form-review-response').on('click','.add-color-and-photo',function(event){loadingScript();var index=$('.product-photos').length+1;$.ajax({type:"POST",url:'/admin/products/products-photos-and-colors',data:{index:index,},success:function(response){$('.products-photos').append(response);},error:function(response){console.log(response);}});return false;});$('form.form-index-content').on('click','.remove-position',function(event){loadingScript();var countPosition=$('.productMainPages-form div.position-list').length;var index=$(this).data('index');$('#position-'+index).remove();if(countPosition==1){$.ajax({type:"POST",url:'/admin/products-main-pages/add-position',data:{index:0},success:function(response){if(response){$('.productMainPages-form').prepend(response);}},error:function(response){console.log(response);}});}else{var new_index=1;$('div.position-list').each(function(){$(this).find('.panel-title-address').html('Позиция: '+new_index);new_index++;});}});$('form.form-index-content').on('change','.data-type',function(event){loadingScript();var data_type=$(this).val();var index=$(this).data('index');$.ajax({type:"POST",url:'/admin/products-main-pages/select-data-type',data:{data_type:data_type,index:index},success:function(response){if(response){$('#select-data-type-'+index).html(response);}},error:function(response){console.log(response);}});return false;});jQuery(document).ready(function($){$('.cd-dropdown-trigger').on('click',function(event){event.preventDefault();toggleNav(this);});$('.cd-close').on('click',function(event){event.preventDefault();toggleNav(this);});$('.has-children').children('a').on('click',function(event){event.preventDefault();var selected=$(this);selected.next('ul').removeClass('is-hidden').end().parent('.has-children').parent('ul').addClass('move-out');});var submenuDirection=(!$('.cd-dropdown-wrapper').hasClass('open-to-left'))?'right':'left';$('.cd-dropdown-content').menuAim({activate:function(row){$(row).children().addClass('is-active').removeClass('fade-out');if($('.cd-dropdown-content .fade-in').length==0)$(row).children('ul').addClass('fade-in');},deactivate:function(row){$(row).children().removeClass('is-active');if($('li.has-children:hover').length==0||$('li.has-children:hover').is($(row))){$('.cd-dropdown-content').find('.fade-in').removeClass('fade-in');$(row).children('ul').addClass('fade-out')}},exitMenu:function(){$('.cd-dropdown-content').find('.is-active').removeClass('is-active');return true;},submenuDirection:submenuDirection,});$('.go-back').on('click',function(){var selected=$(this),visibleNav=$(this).parent('ul').parent('.has-children').parent('ul');selected.parent('ul').addClass('is-hidden').parent('.has-children').parent('ul').removeClass('move-out');});function toggleNav(e){var navIsVisible=(!$('.cd-dropdown').hasClass('dropdown-is-active'))?true:false;$('#cd-dropdown-'+$(e).data('qaid')).toggleClass('dropdown-is-active',navIsVisible);$(e).toggleClass('dropdown-is-active',navIsVisible);if(!navIsVisible){$('#cd-dropdown-'+$(e).data('qaid')).one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',function(){$('#cd-dropdown-'+$(e).data('qaid')+' .has-children ul').addClass('is-hidden');$('#cd-dropdown-'+$(e).data('qaid')+' .move-out').removeClass('move-out');$('#cd-dropdown-'+$(e).data('qaid')+' .is-active').removeClass('is-active');});}}
if(!Modernizr.input.placeholder){$('[placeholder]').focus(function(){var input=$(this);if(input.val()==input.attr('placeholder')){input.val('');}}).blur(function(){var input=$(this);if(input.val()==''||input.val()==input.attr('placeholder')){input.val(input.attr('placeholder'));}}).blur();$('[placeholder]').parents('form').submit(function(){$(this).find('[placeholder]').each(function(){var input=$(this);if(input.val()==input.attr('placeholder')){input.val('');}})});}});$('.modal-add-products-to-order').on('click',function(){var shop_id=$('#orderitems-shops_id').val();$.ajax({type:'post',data:{shop_id:shop_id},url:'/admin/order/modal-add-products-to-order',success:function(data){$('#products-to-order .modal-body').html(data);$('#products-to-order').modal();},});});$('.add-products-to-order').on('click',function(){index=index+1;$.ajax({type:'post',data:{index:index},url:'/admin/order/add-product-in-table',success:function(data){$(".products-list:last").after(data);},});});$(document).on('submit','form#form-products-to-order',function(event){loadingScript();sum_price=0;var product_id=$('form#form-products-to-order #orderitems-product_id').val();var dimension_ruler_id=$('form#form-products-to-order #orderitems-dimension_ruler_id').val();var shop_id=$('#orderitems-shops_id').val();var url;if($("table#order-table-shop-"+shop_id+" tr").is("#"+shop_id+"-"+product_id+"-"+dimension_ruler_id)){alert("Данный товар уже добавлен в список товаров!");return false;}
var index=$('.product-in-shop-'+shop_id).length;if(typeof index=='undefined'){index=0;}
if($("table").is("#order-table-shop-"+shop_id)){url='/admin/order/add-product-in-table';}else{url='/admin/order/add-shop-table';}
$.ajax({type:'post',data:{index:index,product_id:product_id,dimension_ruler_id:dimension_ruler_id,shop_id:shop_id},url:url,success:function(data){if($("table").is("#order-table-shop-"+shop_id)){$('table#order-table-shop-'+shop_id+' tbody').append(data);}else{$('.shop-table').append(data);}
var shop_sum=shopSumm(shop_id);$('#summ-shop-'+shop_id).html(shop_sum);$('#prepayment-shop-'+shop_id).val(shop_sum);totalSumm();totalPrepayment();$('#products-to-order').modal('hide');},});return false;}).on('submit','form#form-updateparam',function(e){e.preventDefault();});function shopSumm(shop_id){shop_sum=Number($('#delivery-shop-'+shop_id).val());$('.price-order-'+shop_id).each(function(){var price_index=$(this).data('index');var discount=0;var product_qty=Number($('#qty-order-'+price_index).val());var product_price=Number($('#price-order-'+price_index).val());var discount_unit=$('#discount_unit-order-'+price_index).val();var discount_value=$('#discount-order-'+price_index).val();if(discount_unit==1){discount=product_price*discount_value/100;}else if(discount_unit==2){discount=discount_value;}else{alert('Вы не указали ед.изм. для скидки');return false;}
var product_sum=product_qty*(product_price-discount);shop_sum=shop_sum+product_sum;});return shop_sum;}
function totalSumm(){summ=0;$('.summ-shop').each(function(){summ=summ+Number($(this).html());});$('.total-summ').html(summ);}
function totalPrepayment(){summ=0;$('.prepayment-order').each(function(){summ=summ+Number($(this).val());});$('.total-prepayment').html(summ);}
function $_GET(key){var p=window.location.search;p=p.match(new RegExp(key+'=([^&=]+)'));return p?p[1]:null;}
$(document).on('click','.create-link',function(){loadingScript();var getArray=getAllUrlParams(location.href);var q=$('.q-input').val();var category=$_GET('category');var sizes=$_GET('sizes');var colors=$_GET('colors');var brands=$_GET('brands');var seasons=$_GET('seasons');var floor=$_GET('floor');var article=$_GET('id');var param=$_GET('param');getParam="";parameters=[];$.ajax({type:'post',data:{sizes:sizes,colors:colors,brands:brands,seasons:seasons,floor:floor,article:article,category:category,param:param,q:q},url:'/admin/products/create-link',success:function(data){$('.create-link').html(data);var $temp=$("<input>");$("body").append($temp);$temp.val($('.create-link').text()).select();document.execCommand("copy");$temp.remove();},});return false;});$(document).on('submit','form#form-filter',function(event){loadingScript();var url=getUrl();var get='';var getArray=getAllUrlParams(location.href);if(getArray.q){get=get+'?q='+getArray.q;}
if(getArray.param){var paramUrl='param='+getArray.param;if(get==''){get='?'+paramUrl;}else{get=get+'&'+paramUrl;}}
var category=getCheckedCheckBoxes('filter-category');var categoryUrl=getParamUrl('category',category);if(categoryUrl!='category='){if(get==''){get='?'+categoryUrl;}else{get=get+'&'+categoryUrl;}}
var sizes=getCheckedCheckBoxes('filter-sizes');var sizeUrl=getParamUrl('sizes',sizes);if(sizeUrl!='sizes='){if(get==''){get='?'+sizeUrl;}else{get=get+'&'+sizeUrl;}}
var display=getCheckedCheckBoxes('filter-display');var displayUrl=getParamUrl('display',display);if(displayUrl!='display='){if(get==''){get='?'+displayUrl;}else{get=get+'&'+displayUrl;}}
var status=getCheckedCheckBoxes('filter-status');var statusUrl=getParamUrl('status',status);if(statusUrl!='status='){if(get==''){get='?'+statusUrl;}else{get=get+'&'+statusUrl;}}
var colors=getCheckedCheckBoxes('filter-colors');var colorUrl=getParamUrl('colors',colors);if(colorUrl!='colors='){if(get==''){get='?'+colorUrl;}else{get=get+'&'+colorUrl;}}
var brands=getCheckedCheckBoxes('filter-brands');var brandUrl=getParamUrl('brands',brands);if(brandUrl!='brands='){if(get==''){get='?'+brandUrl;}else{get=get+'&'+brandUrl;}}
var seasons=getCheckedCheckBoxes('filter-seasons');var seasonUrl=getParamUrl('seasons',seasons);if(seasonUrl!='seasons='){if(get==''){get='?'+seasonUrl;}else{get=get+'&'+seasonUrl;}}
var floor=getCheckedCheckBoxes('filter-floor');var floorUrl=getParamUrl('floor',floor);if(floorUrl!='floor='){if(get==''){get='?'+floorUrl;}else{get=get+'&'+floorUrl;}}
url=url+get;$(location).attr('href',url);return false;}).on('submit','form#form-updateparam',function(e){e.preventDefault();});$(document).on('submit','form#form-param',function(event){loadingScript();var url=getUrl();var get='';userParamGroup='';$('.userParamGroup').each(function(){});var category=getCheckedCheckBoxes('filter-category');var categoryUrl=getParamUrl('category',category);var colors=getCheckedCheckBoxes('filter-colors');var colorUrl=getParamUrl('colors',colors);var brands=getCheckedCheckBoxes('filter-brands');var brandUrl=getParamUrl('brands',brands);var seasons=getCheckedCheckBoxes('filter-seasons');var seasonUrl=getParamUrl('seasons',seasons);var floor=getCheckedCheckBoxes('filter-floor');var floorUrl=getParamUrl('floor',floor);if(categoryUrl!='category='){if(get==''){get='?'+categoryUrl;}else{get=get+'&'+categoryUrl;}}
if(sizeUrl!='sizes='){if(get==''){get='?'+sizeUrl;}else{get=get+'&'+sizeUrl;}}
if(colorUrl!='colors='){if(get==''){get='?'+colorUrl;}else{get=get+'&'+colorUrl;}}
if(brandUrl!='brands='){if(get==''){get='?'+brandUrl;}else{get=get+'&'+brandUrl;}}
if(seasonUrl!='seasons='){if(get==''){get='?'+seasonUrl;}else{get=get+'&'+seasonUrl;}}
if(floorUrl!='floor='){if(get==''){get='?'+floorUrl;}else{get=get+'&'+floorUrl;}}
url=url+get;$(location).attr('href',url);return false;}).on('submit','form#form-updateparam',function(e){e.preventDefault();});$(document).on('click','.close-param',function(event){loadingScript();var url=getUrl();var get='';var name=$(this).data('name');var id=$(this).data('id');var del_param=[];switch($(this).data('name')){case'Цвета':del_param={colors:id};break;case'Бренды':del_param={brands:id};break;case'Размеры':del_param={sizes:id};break;case'Сезоны':del_param={seasons:id};break;case'Пол':del_param={floor:id};break;case'Категория':del_param={category:id};break;}
var getArray=getAllUrlParams(location.href);if(getArray.q){get=get+'?q='+getArray.q;}
var category=getCheckedCheckBoxes('filter-category');var categoryUrl=getParamUrl('category',category,del_param.category);var sizes=getCheckedCheckBoxes('filter-sizes');var sizeUrl=getParamUrl('sizes',sizes,del_param.sizes);var colors=getCheckedCheckBoxes('filter-colors');var colorUrl=getParamUrl('colors',colors,del_param.colors);var brands=getCheckedCheckBoxes('filter-brands');var brandUrl=getParamUrl('brands',brands,del_param.brands);var seasons=getCheckedCheckBoxes('filter-seasons');var seasonUrl=getParamUrl('seasons',seasons,del_param.seasons);var floor=getCheckedCheckBoxes('filter-floor');var floorUrl=getParamUrl('floor',floor,del_param.floor);if(categoryUrl!='category='){if(get==''){get='?'+categoryUrl;}else{get=get+'&'+categoryUrl;}}
if(sizeUrl!='sizes='){if(get==''){get='?'+sizeUrl;}else{get=get+'&'+sizeUrl;}}
if(colorUrl!='colors='){if(get==''){get='?'+colorUrl;}else{get=get+'&'+colorUrl;}}
if(brandUrl!='brands='){if(get==''){get='?'+brandUrl;}else{get=get+'&'+brandUrl;}}
if(seasonUrl!='seasons='){if(get==''){get='?'+seasonUrl;}else{get=get+'&'+seasonUrl;}}
if(floorUrl!='floor='){if(get==''){get='?'+floorUrl;}else{get=get+'&'+floorUrl;}}
url=url+get;$(location).attr('href',url);return false;});$(document).on('click','.article-button',function(event){loadingScript();var url=getUrl();var get='';get='?id='+$('.article-input').val();url=url+get;$(location).attr('href',url);return false;});$(document).on('keydown','.article-input',function(e){if(e.keyCode===13){loadingScript();var url=getUrl();var get='';get='?id='+$(this).val();url=url+get;$(location).attr('href',url);return false;}});$(document).on('click','.q-button',function(event){loadingScript();var url=getUrl();var get='?q='+$('.q-input').val();var getArray=getAllUrlParams(location.href);if(getArray.colors){get=get+'&colors='+getArray.colors;}
if(getArray.brands){get=get+'&brands='+getArray.brands;}
if(getArray.seasons){get=get+'&seasons='+getArray.seasons;}
if(getArray.sizes){get=get+'&sizes='+getArray.sizes;}
if(getArray.floor){get=get+'&floor='+getArray.floor;}
if(getArray.param){get=get+'&param='+getArray.param;}
url=url+get;$(location).attr('href',url);return false;});$(document).on('keydown','.q-input',function(e){if(e.keyCode===13){loadingScript();var url=getUrl();var get='?q='+$('.q-input').val();var getArray=getAllUrlParams(location.href);if(getArray.colors){get=get+'&colors='+getArray.colors;}
if(getArray.brands){get=get+'&brands='+getArray.brands;}
if(getArray.seasons){get=get+'&seasons='+getArray.seasons;}
if(getArray.sizes){get=get+'&sizes='+getArray.sizes;}
if(getArray.floor){get=get+'&floor='+getArray.floor;}
if(getArray.param){get=get+'&param='+getArray.param;}
url=url+get;$(location).attr('href',url);return false;}});function getUrl(){var thisUrl=location.href;var pos=thisUrl.lastIndexOf("?",thisUrl.length);var url=thisUrl.substr(0,pos)
return url;}
function getParamUrl(name,param,id_tag){if(id_tag!=undefined){position=param.indexOf(String(id_tag));if(~position)param.splice(position,1);}
paramUrl=name+'='+param.join();return paramUrl;}
function getCheckedCheckBoxes(classes){var checkboxes=document.getElementsByClassName(classes);var checkboxesChecked=[];for(var index=0;index<checkboxes.length;index++){if(checkboxes[index].checked){checkboxesChecked.push(checkboxes[index].value);}}
return checkboxesChecked;}
$('.modal-sending-payments').on('click',function(event){var id=$(this).data('id');$.ajax({type:"POST",url:'/admin/payment/sending-payments-in-modal',data:{"id":id},success:function(response){$('#modal-payments .modal-body').html(response);$('#modal-payments').modal();},error:function(response){console.log(response);}});});$(document).on('submit','form#modal-sending-payment',function(event){loadingScript();var id=$('form#modal-sending-payment').data('id');var data=$(this).serialize();$.ajax({type:"POST",url:'/admin/payment/sending-payments-in-modal',data:data,success:function(response){if(response){$('#modal-payments .modal-body').html(response);$('#status-requisites-sent-'+id).html('<i class="status-icon payments-icon fas fa-credit-card fa-lg"></i> Реквизиты отправлены');$('#status-requisites-sent-'+id).removeClass('order_status_off').addClass('order_status_on');$('#modal-payments').modal();}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#modal-sending-payment',function(e){e.preventDefault();});$('.modal-sending-prepayments').on('click',function(event){var id=$(this).data('id');$.ajax({type:"POST",url:'/admin/order/sending-prepayments-in-modal',data:{Order:{"id":id},},success:function(response){$('#modal-payments .modal-body').html(response);$('#modal-payments').modal();},error:function(response){console.log(response);}});});$(document).on('submit','form#modal-sending-prepayments',function(event){loadingScript();var id=$('form#modal-sending-prepayments').data('id');var data=$(this).serialize();$.ajax({type:"POST",url:'/admin/order/sending-prepayments-in-modal',data:data,success:function(response){if(response){$('#modal-payments .modal-body').html(response);$('#status-prepayment-'+id).html('<i class="status-icon payments-icon fas fa-money fa-lg"></i> Предоплата внесена');$('#status-prepayment-'+id).removeClass('order_status_off').addClass('order_status_on');$('#modal-payments').modal();}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#modal-sending-prepayments',function(e){e.preventDefault();});$('.modal-payment').on('click',function(event){var id=$(this).data('id');$.ajax({type:"POST",url:'/admin/order/payment-order-in-modal',data:{id:id},success:function(response){$('#modal-payments .modal-body').html(response);$('#modal-payments').modal();},error:function(response){console.log(response);}});});$(document).on('submit','form#payment-order-in-modal',function(event){loadingScript();var id=$('form#payment-order-in-modal').data('id');$.ajax({type:"POST",url:'/admin/order/payment-order-in-modal',data:{id:id,save:true},success:function(response){if(response){$('#modal-payments .modal-body').html(response);$('#status-payment-'+id).html('<i class="status-icon payments-icon fas fa-money fa-lg"></i> Оплата внесена');$('#status-payment-'+id).removeClass('order_status_off').addClass('order_status_on');$('#modal-payments').modal();}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#payment-order-in-modal',function(e){e.preventDefault();});$('.modal-sending-order').on('click',function(event){var id=$(this).data('id');$.ajax({type:"POST",url:'/admin/order/sending-order-in-modal',data:{Order:{"id":id},},success:function(response){$('#modal-payments .modal-body').html(response);$('#modal-payments').modal();},error:function(response){console.log(response);}});});$(document).on('submit','form#sending-order-in-modal',function(event){loadingScript();var id=$('form#sending-order-in-modal').data('id');var data=$(this).serialize();$.ajax({type:"POST",url:'/admin/order/sending-order-in-modal',data:data,success:function(response){if(response){$('#modal-payments .modal-body').html(response);$('#status-send-'+id).html('<i class="status-icon payments-icon fas fa-truck fa-lg"></i> Заказ отправлен');$('#status-send-'+id).removeClass('order_status_off').addClass('order_status_on');$('#modal-payments').modal();}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#sending-order-in-modal',function(e){e.preventDefault();});$('.modal-delivery-order').on('click',function(event){var id=$(this).data('id');$.ajax({type:"POST",url:'/admin/order/delivery-order-in-modal',data:{Order:{"id":id},},success:function(response){$('#modal-payments .modal-body').html(response);$('#modal-payments').modal();},error:function(response){console.log(response);}});});$(document).on('submit','form#delivery-order-in-modal',function(event){loadingScript();var id=$('form#delivery-order-in-modal').data('id');var data=$(this).serialize();$.ajax({type:"POST",url:'/admin/order/delivery-order-in-modal',data:data,success:function(response){if(response){$('#modal-payments .modal-body').html(response);$('#status-delivery-'+id).html('<i class="status-icon payments-icon fas fa-truck fa-lg"></i> Заказ доставлен');$('#status-delivery-'+id).removeClass('order_status_off').addClass('order_status_on');$('#modal-payments').modal();}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#delivery-order-in-modal',function(e){e.preventDefault();});$('.modal-receipt').on('click',function(event){var id=$(this).data('id');$.ajax({type:"POST",url:'/admin/order/receipt-order-in-modal',data:{id:id},success:function(response){$('#modal-payments .modal-body').html(response);$('#modal-payments').modal();},error:function(response){console.log(response);}});});$(document).on('submit','form#receipt-order-in-modal',function(event){loadingScript();var id=$('form#receipt-order-in-modal').data('id');$.ajax({type:"POST",url:'/admin/order/receipt-order-in-modal',data:{id:id,save:true},success:function(response){if(response){$('#modal-payments .modal-body').html(response);$('#status-receipt-'+id).html('<i class="status-icon payments-icon fas fa-shopping-bag fa-lg"></i> Заказ получен');$('#status-receipt-'+id).removeClass('order_status_off').addClass('order_status_on');$('#modal-payments').modal();}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#receipt-order-in-modal',function(e){e.preventDefault();});$('.modal-cancel').on('click',function(event){var id=$(this).data('id');$.ajax({type:"POST",url:'/admin/order/cancel-order-in-modal',data:{Order:{id:id}},success:function(response){$('#modal-payments .modal-body').html(response);$('#modal-payments').modal();},error:function(response){console.log(response);}});});$(document).on('submit','form#cancel-order-in-modal',function(event){loadingScript();var id=$('form#cancel-order-in-modal').data('id');var data=$(this).serialize();$.ajax({type:"POST",url:'/admin/order/cancel-order-in-modal',data:data,success:function(response){if(response){$('#modal-payments .modal-body').html(response);$('#status-cancel-'+id).html('<i class="status-icon payments-icon fas fa-shopping-bag fa-lg"></i> Заказ отменен');$('#status-cancel-'+id).removeClass('order_status_off').addClass('order_status_on');$('#modal-payments').modal();}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#receipt-order-in-modal',function(e){e.preventDefault();});function getAllUrlParams(url){var queryString=url?url.split('?')[1]:window.location.search.slice(1);var obj={};if(queryString){queryString=queryString.split('#')[0];var arr=queryString.split('&');for(var i=0;i<arr.length;i++){var a=arr[i].split('=');var paramNum=undefined;var paramName=a[0].replace(/\[\d*\]/,function(v){paramNum=v.slice(1,-1);return'';});var paramValue=typeof(a[1])==='undefined'?true:a[1];paramName=paramName.toLowerCase();paramValue=paramValue.toLowerCase();if(obj[paramName]){if(typeof obj[paramName]==='string'){obj[paramName]=[obj[paramName]];}
if(typeof paramNum==='undefined'){obj[paramName].push(paramValue);}
else{obj[paramName][paramNum]=paramValue;}}
else{obj[paramName]=paramValue;}}}
return obj;}
jQuery(document).ready(function($){var dd=getAllUrlParams();var dd=getAllUrlParams().page;});$(document).on('click','.dim-rull-link',function(){loadingScript();var dimrul_id=$(this).data('id');$.ajax({type:'post',data:{dimrul_id:dimrul_id},url:'/admin/products/dimension-ruller-info',success:function(data){$('#modal-dimensionRullerInfo .modal-body').html(data);$('#modal-dimensionRullerInfo').modal();},});return false;});$(document).on('submit','form#categories-characteristics',function(event){loadingScript();var data=$(this).serialize();$.ajax({type:"POST",url:'/admin/categories/characterstics-update',data:data,success:function(response){if(response){$('#characteristic').html(response);}else{alert("Не удалось завершить задачу. Попробуйте позже!");}},error:function(response){console.log(response);}});return false;}).on('submit','form#categories-characteristics',function(e){e.preventDefault();});$(document).on('submit','form#form-updateparam-from-guest',function(event){var parameters=[];var scan=false;getParam="";$(this).find('input.userParamGroup').each(function(){var input_id=$(this).data('param_id');var input_value=$(this).val();if(input_id&&input_value){parameters[input_id]=input_value;getParam=getParam+"&parameters_"+input_id+"="+input_value;scan=true;}});if(!scan){$('.error-param').html('<div class="error-select-size" style="display: block;"><i class="fas fa-info-circle"></i> Укажите хотя бы один параметр</div>');return false;}else{$('.error-param').html('');}
var floor=$(this).find('select.userFloor').val();var html='<div class="col-sm-12">'+'<div class="tabs">'+'<ul class="nav nav-justified-off">'+'<li class="">Поиск товаров по указаным параметрам</li>'+'</ul>'+'</div>'+'<div class="sidebar-widget">'+'<div class="col-sm-12">'+'<div class="features_items">'+'<div class="progress-bar-block">'+'<div class="progress">'+'<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>'+'</div>'+'<div class="text-center ">'+'<strong class="progress-message" style="color:red;">Во время поиска не перезагружайте страницу!</strong>'+'</div>'+'</div>'+'</div>'+'</div>'+'</div>'+'</div>';$('#modal-dimensionRullerInfo .modal-body').html(html);$('#modal-dimensionRullerInfo').modal();var myVar=setInterval(function(){ls_ajax_progress_index();},100);$.ajax({type:'post',data:{Parameters:parameters,floor:floor,},url:'/admin/products/updateparam',success:function(data){var url=getUrl();var get;get='?param='+data;get=get+getParam;get=get+'&floor='+floor;var getArray=getAllUrlParams(location.href);if(getArray.q){get=get+'&q='+getArray.q;}
if(getArray.colors){get=get+'&colors='+getArray.colors;}
if(getArray.brands){get=get+'&brands='+getArray.brands;}
if(getArray.seasons){get=get+'&seasons='+getArray.seasons;}
url=url+get;$(location).attr('href',url);clearInterval(myVar);},});return false;}).on('submit','form#form-updateparam-from-guest',function(e){e.preventDefault();});var user_param_group=false;$('form#form-updateparam-from-guest').on('focus','.paramGroup input',function(){if(!user_param_group){$('#param-info').modal();user_param_group=true;}});function ls_ajax_progress_index(){$.ajax({url:'/admin/products/updateparam-progress',success:function(data){$('.progress-bar').attr('style','width: '+data+'%').attr('aria-valuenow',data).html(data+'%');},});return false;}
var StickyElement=function(node){var doc=$(document),fixed=false,anchor=node.find('.sticky-anchor'),content=node.find('.sticky-content');var onScroll=function(e){var docTop=doc.scrollTop(),anchorTop=anchor.offset().top;console.log('scroll',docTop,anchorTop);if(docTop>anchorTop){if(!fixed){anchor.height(content.outerHeight());content.addClass('fixed');fixed=true;}}else{if(fixed){anchor.height(50);content.removeClass('fixed');fixed=false;}}};$(window).on('scroll',onScroll);};var demo=new StickyElement($('#sticky'));$('.checkbox_products_input').on('change',function(e){var el=e.target||e.srcElement;var qwe=$('.checkbox_product');for(var i=0;i<qwe.length;i++)
{if(el.checked)
{qwe[i].checked=true;document.getElementById('cd-dropdown-wrapper-1').style.display='block';}else{qwe[i].checked=false;document.getElementById('cd-dropdown-wrapper-1').style.display='none';}}});$('.checkbox_product').on('change',function(e){var el=e.target||e.srcElement;var qwe=$('.checkbox_product');var select=false;for(var i=0;i<qwe.length;i++)
{if(qwe[i].checked==true)
{select=true;}}
if(select==true){document.getElementById('cd-dropdown-wrapper-1').style.display='block';}else{document.getElementById('cd-dropdown-wrapper-1').style.display='none';}});$('.removeItem').click(function(event){if(confirm("Вы действительно хотите удалить выбранные позиции?")){var qwe=$('.checkbox_product');let products_id=[];for(var i=0;i<qwe.length;i++)
{if(qwe[i].checked==true)
{products_id.push(qwe[i].value);}}
$.ajax({url:'/admin/products/delete-products',type:"POST",data:{products_id:products_id},success:function(res){alert(1);}});}});$('.removeOrderItem').click(function(event){if(confirm("Вы действительно хотите удалить выбранные позиции?")){var qwe=$('.checkbox_order');let orders_id=[];for(var i=0;i<qwe.length;i++)
{if(qwe[i].checked==true)
{orders_id.push(qwe[i].value);}}
$.ajax({url:'/admin/order/delete-orders',type:"POST",data:{orders_id:orders_id},success:function(res){alert(1);}});}});$('.checkbox_order').on('change',function(e){var el=e.target||e.srcElement;var qwe=$('.checkbox_order');var select=false;for(var i=0;i<qwe.length;i++)
{if(qwe[i].checked==true)
{select=true;}}
if(select==true){document.getElementById('cd-dropdown-wrapper-1').style.display='block';}else{document.getElementById('cd-dropdown-wrapper-1').style.display='none';}});;(function($){$.fn.menuAim=function(opts){this.each(function(){init.call(this,opts);});return this;};function init(opts){var $menu=$(this),activeRow=null,mouseLocs=[],lastDelayLoc=null,timeoutId=null,options=$.extend({rowSelector:"> li",submenuSelector:"*",submenuDirection:"right",tolerance:75,enter:$.noop,exit:$.noop,activate:$.noop,deactivate:$.noop,exitMenu:$.noop},opts);var MOUSE_LOCS_TRACKED=3,DELAY=300;var mousemoveDocument=function(e){mouseLocs.push({x:e.pageX,y:e.pageY});if(mouseLocs.length>MOUSE_LOCS_TRACKED){mouseLocs.shift();}};var mouseleaveMenu=function(){if(timeoutId){clearTimeout(timeoutId);}
if(options.exitMenu(this)){if(activeRow){options.deactivate(activeRow);}
activeRow=null;}};var mouseenterRow=function(){if(timeoutId){clearTimeout(timeoutId);}
options.enter(this);possiblyActivate(this);},mouseleaveRow=function(){options.exit(this);};var clickRow=function(){activate(this);};var activate=function(row){if(row==activeRow){return;}
if(activeRow){options.deactivate(activeRow);}
options.activate(row);activeRow=row;};var possiblyActivate=function(row){var delay=activationDelay();if(delay){timeoutId=setTimeout(function(){possiblyActivate(row);},delay);}else{activate(row);}};var activationDelay=function(){if(!activeRow||!$(activeRow).is(options.submenuSelector)){return 0;}
var offset=$menu.offset(),upperLeft={x:offset.left,y:offset.top-options.tolerance},upperRight={x:offset.left+$menu.outerWidth(),y:upperLeft.y},lowerLeft={x:offset.left,y:offset.top+$menu.outerHeight()+options.tolerance},lowerRight={x:offset.left+$menu.outerWidth(),y:lowerLeft.y},loc=mouseLocs[mouseLocs.length-1],prevLoc=mouseLocs[0];if(!loc){return 0;}
if(!prevLoc){prevLoc=loc;}
if(prevLoc.x<offset.left||prevLoc.x>lowerRight.x||prevLoc.y<offset.top||prevLoc.y>lowerRight.y){return 0;}
if(lastDelayLoc&&loc.x==lastDelayLoc.x&&loc.y==lastDelayLoc.y){return 0;}
function slope(a,b){return(b.y-a.y)/(b.x-a.x);};var decreasingCorner=upperRight,increasingCorner=lowerRight;if(options.submenuDirection=="left"){decreasingCorner=lowerLeft;increasingCorner=upperLeft;}else if(options.submenuDirection=="below"){decreasingCorner=lowerRight;increasingCorner=lowerLeft;}else if(options.submenuDirection=="above"){decreasingCorner=upperLeft;increasingCorner=upperRight;}
var decreasingSlope=slope(loc,decreasingCorner),increasingSlope=slope(loc,increasingCorner),prevDecreasingSlope=slope(prevLoc,decreasingCorner),prevIncreasingSlope=slope(prevLoc,increasingCorner);if(decreasingSlope<prevDecreasingSlope&&increasingSlope>prevIncreasingSlope){lastDelayLoc=loc;return DELAY;}
lastDelayLoc=null;return 0;};$menu.mouseleave(mouseleaveMenu).find(options.rowSelector).mouseenter(mouseenterRow).mouseleave(mouseleaveRow).click(clickRow);$(document).mousemove(mousemoveDocument);};})(jQuery);;;window.Modernizr=function(a,b,c){function C(a){j.cssText=a}function D(a,b){return C(n.join(a+";")+(b||""))}function E(a,b){return typeof a===b}function F(a,b){return!!~(""+a).indexOf(b)}function G(a,b){for(var d in a){var e=a[d];if(!F(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function H(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:E(f,"function")?f.bind(d||b):f}return!1}function I(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+p.join(d+" ")+d).split(" ");return E(b,"string")||E(b,"undefined")?G(e,b):(e=(a+" "+q.join(d+" ")+d).split(" "),H(e,b,c))}function J(){e.input=function(c){for(var d=0,e=c.length;d<e;d++)u[c[d]]=c[d]in k;return u.list&&(u.list=!!b.createElement("datalist")&&!!a.HTMLDataListElement),u}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),e.inputtypes=function(a){for(var d=0,e,f,h,i=a.length;d<i;d++)k.setAttribute("type",f=a[d]),e=k.type!=="text",e&&(k.value=l,k.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(f)&&k.style.WebkitAppearance!==c?(g.appendChild(k),h=b.defaultView,e=h.getComputedStyle&&h.getComputedStyle(k,null).WebkitAppearance!=="textfield"&&k.offsetHeight!==0,g.removeChild(k)):/^(search|tel)$/.test(f)||(/^(url|email)$/.test(f)?e=k.checkValidity&&k.checkValidity()===!1:e=k.value!=l)),t[a[d]]=!!e;return t}("search tel url email datetime date month week time datetime-local number range color".split(" "))}var d="2.8.3",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k=b.createElement("input"),l=":)",m={}.toString,n=" -webkit- -moz- -o- -ms- ".split(" "),o="Webkit Moz O ms",p=o.split(" "),q=o.toLowerCase().split(" "),r={svg:"http://www.w3.org/2000/svg"},s={},t={},u={},v=[],w=v.slice,x,y=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},z=function(){function d(d,e){e=e||b.createElement(a[d]||"div"),d="on"+d;var f=d in e;return f||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(d,""),f=E(e[d],"function"),E(e[d],"undefined")||(e[d]=c),e.removeAttribute(d))),e=null,f}var a={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return d}(),A={}.hasOwnProperty,B;!E(A,"undefined")&&!E(A.call,"undefined")?B=function(a,b){return A.call(a,b)}:B=function(a,b){return b in a&&E(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=w.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(w.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(w.call(arguments)))};return e}),s.flexbox=function(){return I("flexWrap")},s.canvas=function(){var a=b.createElement("canvas");return!!a.getContext&&!!a.getContext("2d")},s.canvastext=function(){return!!e.canvas&&!!E(b.createElement("canvas").getContext("2d").fillText,"function")},s.webgl=function(){return!!a.WebGLRenderingContext},s.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:y(["@media (",n.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},s.geolocation=function(){return"geolocation"in navigator},s.postmessage=function(){return!!a.postMessage},s.websqldatabase=function(){return!!a.openDatabase},s.indexedDB=function(){return!!I("indexedDB",a)},s.hashchange=function(){return z("hashchange",a)&&(b.documentMode===c||b.documentMode>7)},s.history=function(){return!!a.history&&!!history.pushState},s.draganddrop=function(){var a=b.createElement("div");return"draggable"in a||"ondragstart"in a&&"ondrop"in a},s.websockets=function(){return"WebSocket"in a||"MozWebSocket"in a},s.rgba=function(){return C("background-color:rgba(150,255,150,.5)"),F(j.backgroundColor,"rgba")},s.hsla=function(){return C("background-color:hsla(120,40%,100%,.5)"),F(j.backgroundColor,"rgba")||F(j.backgroundColor,"hsla")},s.multiplebgs=function(){return C("background:url(https://),url(https://),red url(https://)"),/(url\s*\(.*?){3}/.test(j.background)},s.backgroundsize=function(){return I("backgroundSize")},s.borderimage=function(){return I("borderImage")},s.borderradius=function(){return I("borderRadius")},s.boxshadow=function(){return I("boxShadow")},s.textshadow=function(){return b.createElement("div").style.textShadow===""},s.opacity=function(){return D("opacity:.55"),/^0.55$/.test(j.opacity)},s.cssanimations=function(){return I("animationName")},s.csscolumns=function(){return I("columnCount")},s.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return C((a+"-webkit- ".split(" ").join(b+a)+n.join(c+a)).slice(0,-a.length)),F(j.backgroundImage,"gradient")},s.cssreflections=function(){return I("boxReflect")},s.csstransforms=function(){return!!I("transform")},s.csstransforms3d=function(){var a=!!I("perspective");return a&&"webkitPerspective"in g.style&&y("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},s.csstransitions=function(){return I("transition")},s.fontface=function(){var a;return y('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&g.indexOf(d.split(" ")[0])===0}),a},s.generatedcontent=function(){var a;return y(["#",h,"{font:0/0 a}#",h,':after{content:"',l,'";visibility:hidden;font:3px/1 a}'].join(""),function(b){a=b.offsetHeight>=3}),a},s.video=function(){var a=b.createElement("video"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,"")}catch(d){}return c},s.audio=function(){var a=b.createElement("audio"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,"")}catch(d){}return c},s.localstorage=function(){try{return localStorage.setItem(h,h),localStorage.removeItem(h),!0}catch(a){return!1}},s.sessionstorage=function(){try{return sessionStorage.setItem(h,h),sessionStorage.removeItem(h),!0}catch(a){return!1}},s.webworkers=function(){return!!a.Worker},s.applicationcache=function(){return!!a.applicationCache},s.svg=function(){return!!b.createElementNS&&!!b.createElementNS(r.svg,"svg").createSVGRect},s.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==r.svg},s.smil=function(){return!!b.createElementNS&&/SVGAnimate/.test(m.call(b.createElementNS(r.svg,"animate")))},s.svgclippaths=function(){return!!b.createElementNS&&/SVGClipPath/.test(m.call(b.createElementNS(r.svg,"clipPath")))};for(var K in s)B(s,K)&&(x=K.toLowerCase(),e[x]=s[K](),v.push((e[x]?"":"no-")+x));return e.input||J(),e.addTest=function(a,b){if(typeof a=="object")for(var d in a)B(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},C(""),i=k=null,function(a,b){function l(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function m(){var a=s.elements;return typeof a=="string"?a.split(" "):a}function n(a){var b=j[a[h]];return b||(b={},i++,a[h]=i,j[i]=b),b}function o(a,c,d){c||(c=b);if(k)return c.createElement(a);d||(d=n(c));var g;return d.cache[a]?g=d.cache[a].cloneNode():f.test(a)?g=(d.cache[a]=d.createElem(a)).cloneNode():g=d.createElem(a),g.canHaveChildren&&!e.test(a)&&!g.tagUrn?d.frag.appendChild(g):g}function p(a,c){a||(a=b);if(k)return a.createDocumentFragment();c=c||n(a);var d=c.frag.cloneNode(),e=0,f=m(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function q(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return s.shivMethods?o(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+m().join().replace(/[\w\-]+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(s,b.frag)}function r(a){a||(a=b);var c=n(a);return s.shivCSS&&!g&&!c.hasCSS&&(c.hasCSS=!!l(a,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),k||q(a,c),a}var c="3.7.0",d=a.html5||{},e=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,f=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,g,h="_html5shiv",i=0,j={},k;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",g="hidden"in a,k=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){g=!0,k=!0}})();var s={elements:d.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",version:c,shivCSS:d.shivCSS!==!1,supportsUnknownElements:k,shivMethods:d.shivMethods!==!1,type:"default",shivDocument:r,createElement:o,createDocumentFragment:p};a.html5=s,r(b)}(this,b),e._version=d,e._prefixes=n,e._domPrefixes=q,e._cssomPrefixes=p,e.hasEvent=z,e.testProp=function(a){return G([a])},e.testAllProps=I,e.testStyles=y,e.prefixed=function(a,b,c){return b?I(a,b,c):I(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+v.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};;$(document).on('change','.product_toggle',function(){var product_id=$(this).data('product_id');$.ajax({url:'/seller/products/product-status',type:'POST',data:{product_id:product_id},success:function(res){if(!res)alert('Ошибка!!!');var cart=eval('('+res+')');if(cart['empty']){showEmptyCart();}else{showCart(cart['render']);}},error:function(){alert('Ошибка!');},});return false;});$(document).on('change','.product_main_page_toggle',function(){var product_id=$(this).data('product_id');$.ajax({url:'/seller/products-main-pages/product-status',type:'POST',data:{product_id:product_id},success:function(res){if(!res)alert('Ошибка!!!');var cart=eval('('+res+')');if(cart['empty']){showEmptyCart();}else{showCart(cart['render']);}},error:function(){alert('Ошибка!');},});return false;});$(document).on('change','.product_main_page_toggle_admin',function(){var id=$(this).data('id');$.ajax({url:'/admin/products-main-pages/product-status',type:'POST',data:{id:id},success:function(res){if(!res)alert('Ошибка!!!');var cart=eval('('+res+')');if(cart['empty']){showEmptyCart();}else{showCart(cart['render']);}},error:function(){alert('Ошибка!');},});return false;});$('.products-form').each(function(){var highestBox=0;$('.sett',this).each(function(){if($(this).height()>highestBox){highestBox=$(this).height();}});$('.sett',this).height(highestBox);});$(document).on('click','#imageUpload',function(){var parent_li=$(this).parent('li');$(parent_li).attr('class','hidden');$(parent_li).find('input').val('');$(parent_li).find('a').attr('href','/images/ico/no-photo.png');$(parent_li).find('.croppedImg').attr('src','/images/ico/no-photo.png');});function showClearCart(cart){$('#clearcart .modal-body').html(cart);$('#clearcart').modal();};$(function(){$('a.linktip').wrap('<span class="tip" />');$('span.tip').each(function(){myTip=$(this),tipLink=myTip.children('a'),tBlock=myTip.children('span').length,tTitle=tipLink.attr('title')!=0,tipText=tipLink.attr('title');tipLink.removeAttr("title");if(tBlock===0&&tTitle===true){myTip.append('<span class="answer">'+tipText+'</span>')};var tip=myTip.find('span.answer , span.answer-left').hide();tipLink.has('em').click(showTip).siblings('span').append('<b class="close">X</b>');tipLink.not($('em').parent()).hoverIntent(showTip,function(){tip.fadeOut(200);});tip.on('click','.close',function(){tip.fadeOut(200);});function showTip(e){xM=e.pageX,yM=e.pageY,tipW=tip.width(),tipH=tip.height(),winW=$(window).width(),winH=$(window).height(),scrollwinH=$(window).scrollTop(),scrollwinW=$(window).scrollLeft(),curwinH=$(window).scrollTop()+$(window).height();if(xM>scrollwinW+tipW*2){tip.removeClass('answer').addClass('answer-left');}
else{tip.removeClass('answer-left').addClass('answer');}
if(yM>scrollwinH+tipH&&yM>curwinH/2){tip.addClass('a-top');}
else{tip.removeClass('a-top');}
tip.fadeIn(100).css('display','block');e.preventDefault();};});});;(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);;