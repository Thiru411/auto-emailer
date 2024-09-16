<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
	<?php $this->load->view("inc/common-header");?>
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<?php $this->load->view("inc/header-nav");?>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<?php $this->load->view("inc/side-nav");?>
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Toolbar-->
							<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
									<!--begin::Page title-->
									<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
										<!--begin::Title-->
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dashboard</h1>
										<!--end::Title-->
										<!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="<?=base_url()?>dashboard/index" class="text-muted text-hover-primary">Home</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Dashboards</li>
											<!--end::Item-->
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page title-->
									<!--begin::Actions-->
									
									<!--end::Actions-->
								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl">
									<!--begin::Row-->
									<div class="row g-5 g-xl-10 mb-xl-10">
										<!--begin::Col-->
										<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
											<!--begin::Card widget 16-->
											<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0  mb-5 mb-xl-10" style="background-color: #080655">
												<!--begin::Header-->
												<div class="card-header pt-5">
													<!--begin::Title-->
													<div class="card-title d-flex flex-column">
														<!--begin::Amount-->
														<span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"><?=$users_count?></span>
														<!--end::Amount-->
														<!--begin::Subtitle-->
														<span class="text-white opacity-50 pt-1 fw-semibold fs-6">Active Users</span>
														<!--end::Subtitle-->
													</div>
													<!--end::Title-->
												</div>
												<!--end::Header-->
												<!--begin::Card body-->
												<div class="card-body d-flex align-items-end pt-0">
													<!--begin::Progress-->
													
													<!--end::Progress-->
												</div>
												<!--end::Card body-->
											</div>
</div>





<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">

<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 mb-5 mb-xl-10" style="background-color: #080655">
												<!--begin::Header-->
												<div class="card-header pt-5">
													<!--begin::Title-->
													<div class="card-title d-flex flex-column">
														<!--begin::Amount-->
														<span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"><?=$customers_count?></span>
														<!--end::Amount-->
														<!--begin::Subtitle-->
														<span class="text-white opacity-50 pt-1 fw-semibold fs-6">Active Clients</span>
														<!--end::Subtitle-->
													</div>
													<!--end::Title-->
												</div>
												<div class="card-body d-flex align-items-end pt-0">
													<!--begin::Progress-->
													
													<!--end::Progress-->
												</div>
												<!--end::Header-->
												<!--begin::Card body-->
												
												<!--end::Card body-->
											</div>
</div>
<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">


<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0  mb-5 mb-xl-10" style="background-color: #080655">
												<!--begin::Header-->
												<div class="card-header pt-5">
													<!--begin::Title-->
													<div class="card-title d-flex flex-column">
														<!--begin::Amount-->
														<span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"><?=$projects_count?></span>
														<!--end::Amount-->
														<!--begin::Subtitle-->
														<span class="text-white opacity-50 pt-1 fw-semibold fs-6">Active Projects</span>
														<!--end::Subtitle-->
													</div>
													<!--end::Title-->
												</div>
												<!--end::Header-->
												<!--begin::Card body-->
												<div class="card-body d-flex align-items-end pt-0">
													<!--begin::Progress-->
													<!--end::Progress-->
												</div>
												<!--end::Card body-->
											</div>
</div>


		<div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">

				<div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0  mb-5 mb-xl-10" style="background-color: #080655">
												<!--begin::Header-->
												<div class="card-header pt-5">
													<!--begin::Title-->
													<div class="card-title d-flex flex-column">
														<!--begin::Amount-->
														<span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2"><?=$today?></span>
														<!--end::Amount-->
														<!--begin::Subtitle-->
														<span class="text-white opacity-50 pt-1 fw-semibold fs-6">Today Alerts</span>
														<!--end::Subtitle-->
													</div>
													<!--end::Title-->
												</div>
												<!--end::Header-->
												<!--begin::Card body-->
												<div class="card-body d-flex align-items-end pt-0">
													<!--begin::Progress-->
													
													<!--end::Progress-->
												</div>
												<!--end::Card body-->
											</div>
											</div>



									
											<!--end::Card widget 16-->
											<!--begin::Card widget 7-->
											
											<!--end::Card widget 7-->
										</div>
										<!--end::Col-->
										<!--begin::Col-->
										
										<!--end::Col-->
										<!--begin::Col-->
										
										<!--end::Col-->
									</div>
									<!--end::Row-->
									<!--begin::Row-->
									
									<!--end::Row-->
									<!--begin::Row-->
									
									<!--end::Row-->
									<!--begin::Row-->
									
									<!--end::Row-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?php  $this->load->view("inc/footer-nav");?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Drawers-->
		<!--begin::Activities drawer-->
		<?php  $this->load->view("inc/common-models");?>
		<!--end::Modal - Invite Friend-->
		<!--end::Modals-->
		<?php  $this->load->view("inc/footer-scripts");?>
	</body>
	<!--end::Body-->
</html>