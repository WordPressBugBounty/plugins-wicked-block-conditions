const { Fragment } = wp.element;

const SliderPanels = ( { children, activePanel } ) => {
	const panels = children.map( ( child, index ) => {
		if ( activePanel != child.props.name ) return;

		return (
			<div key={ index } className="wicked-slider-panel">
				{ child }
			</div>
		);
	} );

	return (
		<div className="wicked-slider-panels">
			{ panels }
		</div>
	)
};

const SliderPanel = ( { children } ) => {
	return (
		<Fragment>
			{ children }
		</Fragment>
	)
}

export { SliderPanels, SliderPanel };
